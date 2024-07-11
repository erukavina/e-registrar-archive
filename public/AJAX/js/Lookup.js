/**
 * LookupAction.js
 * Author: Emanuel Tin Rukavina
 * Contact: emanuel@uncuni.com
 * 
 * Handles the lookup functionality.
 */

$(function () {
    var queryString = window.location.search; // Retrieve the query string from the URL
    var urlParams = new URLSearchParams(queryString); // Create URLSearchParams object from query string
    var qParam = urlParams.get('q'); // Retrieve the value of 'q' parameter from URL
    var xhr; // Variable to store XMLHttpRequest object
    var loadingTimer; // Variable to store loading timer

    // If 'q' parameter exists in the URL, populate the query input and perform lookup
    if (qParam) {
        $('#query').val(decodeURIComponent(qParam)); // Set value of query input field
        lookup(); // Perform lookup
    }

    $("#LookupForm").submit(function (event) {
        event.preventDefault(); // Prevent default form submission behavior
        lookup(); // Call the lookup function
    });

    // Add an unload event handler to abort the ongoing request
    $(window).on('unload', function () {
        if (xhr && xhr.readyState !== 4) {
            xhr.abort(); // Abort the ongoing request if not already completed
        }
    });

    /**
     * Initiates the lookup process by sending AJAX request to the server.
     */
    function lookup() {
        var query = $("#query").val(); // Retrieve query value from input field
        var token = $("#token").val(); // Retrieve CSRF token value

        clearTimeout(loadingTimer); // Clear the loading timer if it's set

        // Check if there's an ongoing request, and abort it if necessary
        if (xhr && xhr.readyState !== 4) {
            xhr.abort(); // Abort the ongoing request if not already completed
        }

        // Initiate the new AJAX request
        xhr = $.ajax({
            type: "POST",
            url: "/AJAX/php/lookup?action=lookup", // URL to send the lookup request
            cache: false,
            data: { query: query }, // Data to be sent in the request
            dataType: "json", // Expected data type of the response
            headers: {
                "X-CSRF-Token": token, // Include CSRF token in request headers
            },
            beforeSend: function () {
                $('#LookupForm :input').prop('disabled', true); // Disable form inputs before sending the request
                DeleteStatus(); // Clear any existing status messages
                SetStatus("primary", "<div style='display:flex;flex-direction: row-reverse;justify-content: space-between;align-items: center;'><div style='scale: .9;' class='spinner-border text-primary' aria-hidden='true'></div> Processing, please wait...</div>"); // Display loading message

                // Set a timer to add the cancel button after 15 seconds
                loadingTimer = setTimeout(function () {
                    SetStatus("warning", "<div><center><div style='scale: .9;' class='spinner-border text-warning' aria-hidden='true'></div><hr>We are having some difficulties finding records, this can take up to 2 minutes. Please wait...</center></div>"); // Display warning message after 15 seconds
                }, 15000);
            },
            success: function (response) {
                console.log(response);
                clearTimeout(loadingTimer); // Clear the loading timer
                if (!response.tlr) { // Check if lookup was unsuccessful
                    if (response.error == "validate_gov") { // Check if error is related to government-issued domains
                        SetStatus("primary", "<h4><i class='bi bi-flag'></i> Government-Issued Domains</h4><hr><p>As a security measure, e-Registrar does not display data from government-issued domains.</p><p>This policy is in place to uphold stringent security standards and prevent potential security vulnerabilities that may arise from accessing government networks.</p><p>We apologize for any inconvenience this may cause and appreciate your understanding.</p>"); // Display message for government-issued domains
                    } else {
                        SetStatus("danger", response.error); // Display general error message
                    }
                } else {
                    clearTimeout(loadingTimer); // Clear the loading timer
                    SetStatus("success", "Success! Redirecting you to the results page now..."); // Display success message
                    window.location.href = '/lookup?q=' + encodeURIComponent(response.query); // Redirect to results page
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                clearTimeout(loadingTimer); // Clear the loading timer
                SetStatus("danger", "Request failed. Please verify your connection and retry. For persistent issues, contact support with the error details."); // Display error message
            },
            complete: function () {
                clearTimeout(loadingTimer); // Clear the loading timer
                $('#LookupForm :input').prop('disabled', false); // Re-enable form inputs after request completes
            }
        });
    }
});

/**
 * Displays a status message with specified type and data.
 * @param {string} type - Type of the status message (e.g., success, danger)
 * @param {string} data - Content of the status message
 */
function SetStatus(type, data) {
    $("#StatusDataLookup").html("<div class='alert alert-" + type + "' role='alert'>" + data + "</div>"); // Display the status message
}

/**
 * Deletes any existing status messages.
 */
function DeleteStatus() {
    $("#StatusDataLookup").html(""); // Clear the status message
}

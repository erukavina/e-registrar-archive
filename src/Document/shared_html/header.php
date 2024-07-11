<nav class="navbar navbar-expand-lg bg-body fixed-top" style="border-bottom: var(--bs-border-width) solid var(--bs-border-color);background-color: rgba(var(--bs-tertiary-bg-rgb)) !important;">
    <div class="container">
        <a class="navbar-brand d-flex" href="/">
            <img src="/assets/media/e-registrar.png" alt="e-Registrar Logo" style="width: 1.5em;margin-inline-end: .3em;">
            <span>e-Registrar</span>
        </a>
        <div class="dropdown  d-flex">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-brightness-high"></i> Theme
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item theme-manager" href="#" data-theme="light"><i class="bi bi-sun"></i> Light</a></li>
                <li><a class="dropdown-item theme-manager" href="#" data-theme="dark"><i class="bi bi-moon"></i> Dark</a></li>
            </ul>
        </div>

    </div>
</nav>
<div class="modal fade" id="FeedbackModal" tabindex="-1" aria-labelledby="FeedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Feedback</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span class="hide">
                    I am providing feedback because:
                </span>
                <select class="form-select mb-3 hide" required id="reason" aria-label="Reason for Feedback">
                    <option selected disabled>Select one of the options</option>
                    <option value="incorrect-information">The information is incorrect</option>
                    <option value="website-purchased">The website is incorrectly marked as not purchased</option>
                    <option value="bug">I encountered a technical issue / bug</option>
                    <option value="suggestion">I have a suggestion for improvement</option>
                    <option value="other">Other (Please specify in your message)</option>
                </select>

                <div class="mb-3 hide">
                    <label for="msg" class="form-label">Your message:</label>
                    <textarea required class="form-control" id="msg" rows="3"></textarea>
                </div>
                <div class="hide">
                    <p>By clicking on "Send," the following information will be provided by the system:</p>
                    <ul>
                        <li>Device you are using</li>
                        <li>About the results provided by the website</li>
                        <li>Session information</li>
                    </ul>
                </div>
                <div id='StatusDataFeedback'></div>
            </div>
            <div class="modal-footer ">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary hide" onclick="Feedback()">Send</button>
            </div>
        </div>
    </div>
</div>

<script>
    function Feedback() {
        DeleteFeedbackStatus(); // Clear any existing status messages

        // Retrieve input values
        var reason = $("#reason").val();
        var msg = $("#msg").val();

        if (!reason) {
            SetStatusFeedback("warning", "Please select a reason for feedback.");
            return; // Exit the function if reason is empty
        }

        // Initiate AJAX request
        $.ajax({
            type: "POST",
            url: "/AJAX/php/feedback", // URL to send the email update request
            cache: false,
            data: {
                reason: reason,
                msg: msg
            },
            dataType: "json",
            beforeSend: function() {
                DeleteFeedbackStatus(); // Clear any existing status messages
            },
            success: function(response) {
                if (!response.tlr) {
                    SetStatusFeedback("danger", "An error occured.");
                } else {
                    $('.hide').hide();
                    SetStatusFeedback("success", "Feedback sent. Thank you.");
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                SetStatusFeedback("danger", "Request failed. Please verify your connection and retry. For persistent issues, contact support with the error details."); // Display error message
            }
        });
    }

    function SetStatusFeedback(type, data) {
        $("#StatusDataFeedback").html("<div class='alert alert-" + type + "' role='alert'>" + data + "</div>"); // Display the status message
    }

    /**
     * Deletes any existing status messages.
     */
    function DeleteFeedbackStatus() {
        $("#StatusDataFeedbackFeedback").html(""); // Clear the status message
    }
</script>
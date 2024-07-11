/**
 * CookieManager.js
 * Author: Emanuel Tin Rukavina
 * Contact: emanuel@uncuni.com
 * 
 * Deals with cookies.
 * 
 */
(function () {
    // Check if the cookie consent has already been given
    function checkCookieConsent() {
        return document.cookie.split(';').some(item => item.trim().startsWith('cookieConsent='));
    }

    // Set the cookie consent
    function setCookieConsent(value) {
        const date = new Date();
        date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000)); // 1 year
        document.cookie = `cookieConsent=${value}; expires=${date.toUTCString()}; path=/`;
    }

    // Create and show the cookie consent modal
    function createCookieConsentModal() {
        const modalHTML = `
            <div class="modal fade" id="cookieConsentModal" tabindex="-1" aria-labelledby="cookieConsentModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog"style="max-width: 38em;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cookieConsentModalLabel">Allow the use of cookies from e-Registrar on this browser?</h5>
                        </div>
                        <div class="modal-body">
                            <div style="height:25em;overflow:scroll;">
                                <p>We use essential cookies and similar technologies to help:</p>

                                <p style="display: flex;">
                                    <i class="bi bi-gear" style="margin-inline-end: .3em;"></i>
                                    <span>
                                    To improve products and services
                                    </span>
                                </p>

                                <p style="display: flex;">
                                    <i class="bi bi-shield-check" style="margin-inline-end: .3em;"></i>
                                    <span>
                                        Provide a safer experience by using information we receive from e-Registrar.com
                                    </span>
                                </p>
                                <hr>
                                <p>We use tools on e-Registrar from other companies that also use cookies. These tools are used for things like:</p>
                                <ul>
                                <li>Advertising and measurement services off of e-Registrar</li>
                                <li>Analytics</li>
                                <li>Providing certain features</li>
                                <li>Improving our services</li>
                                </ul>
                                <br>
                                <p>You can allow the use of all cookies or just essential cookies. You can learn more about cookies and how we use them, and review or change your choice at any time in our <a target="_blank"href="/privacy">Privacy Policy</a>.</p>
                                                     
                        </div>
                        <div class="modal-footer" style="padding-block-end: 0em; display: flex;">
    <button type="button" class="btn btn-sm btn-outline-secondary" style="flex: 1;" id="allowEssentialCookies">Allow Only Essential</button>
    <button type="button" class="btn btn-sm btn-primary" style="flex: 1;" id="acceptAllCookies">Allow All</button>
</div>

                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);

        const consentModal = new bootstrap.Modal(document.getElementById('cookieConsentModal'), {
            keyboard: false,
            backdrop: 'static'
        });

        // Accept All Cookies
        document.getElementById('acceptAllCookies').addEventListener('click', function () {
            setCookieConsent('all');
            consentModal.hide();
            location.reload(); // Reload the page after setting the cookie
        });

        // Allow Only Essential Cookies
        document.getElementById('allowEssentialCookies').addEventListener('click', function () {
            setCookieConsent('essential');
            consentModal.hide();
            location.reload(); // Reload the page after setting the cookie
        });

        return consentModal;
    }

    // Initialize or reuse the modal
    let consentModalInstance = null;

    if (!checkCookieConsent()) {
        consentModalInstance = createCookieConsentModal();
        consentModalInstance.show();
    }

    // Functionality to reopen the cookie consent modal
    document.addEventListener('click', function (e) {
        if (e.target && e.target.id === 'open_preferences_center') {
            e.preventDefault();
            if (!consentModalInstance) {
                consentModalInstance = createCookieConsentModal();
            }
            consentModalInstance.show();
        }
    });
})();

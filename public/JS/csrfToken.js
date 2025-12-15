/**
 * Fetches a CSRF token from the server and injects it into all forms on the page.
 *
 * This function makes an asynchronous request to the `fetchCsrfToken` endpoint.
 * Once the token is retrieved, it dynamically creates a hidden input field
 * with the token and appends it to every form found in the document. This helps
 * protect the application against Cross-Site Request Forgery attacks.
 *
 * @returns {void}
 */
function csrfToken() {
    fetch(window.WEBSITE_URL + "fetchCsrfToken") // Get the CSRF token from the server
        .then(response => response.text())
        .then(token => {
            document.querySelectorAll("form").forEach(form => {
                let hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.id = "csrf_token";
                hiddenInput.value = token;
                form.appendChild(hiddenInput);
            });
        })
        .catch(error => console.error("CSRF token fetch failed", error));
}

// Run function on page load
// window.onload = csrfToken;
csrfToken();

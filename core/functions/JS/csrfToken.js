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

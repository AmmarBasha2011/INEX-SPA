/**
 * Prepends the application name to the document's title.
 *
 * This function retrieves the application name from the global `window.APP_NAME` variable
 * and prepends it to the content of the `<title>` tag in the HTML document. This is
 * used to ensure consistent branding across all pages.
 *
 * @returns {void}
 */
function addAppNameToHTML() {
    // Get the title element
    const titleElement = document.querySelector('title');
    
    // Check if title element and APP_NAME exist
    if (titleElement && window.APP_NAME) {
        // Get current title text
        const currentTitle = titleElement.textContent;
        // Set new title with APP_NAME
        titleElement.textContent = `${window.APP_NAME} - ${currentTitle}`;
    }
}

addAppNameToHTML();

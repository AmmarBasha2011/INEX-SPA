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
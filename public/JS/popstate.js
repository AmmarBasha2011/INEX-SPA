/**
 * Handles browser navigation events (back/forward buttons).
 *
 * This script listens for the `popstate` event, which is triggered when the user
 * navigates through their session history (e.g., using the browser's back or
 * forward buttons). It extracts the last segment of the URL and uses the `redirect`
 * function to load the corresponding page content, ensuring the single-page
 * application stays in sync with the browser's navigation.
 *
 * @param {PopStateEvent} event - The `popstate` event object provided by the browser.
 * @returns {void}
 */
window.addEventListener('popstate', function(event) {
    const currentUrl = window.location.href;
    const baseUrl = window.WEBSITE_URL;
    const remaining = currentUrl.replace(baseUrl, '');
    let lastSegment = remaining.split('/').filter(segment => segment).pop() || '';
    redirect(lastSegment);
});

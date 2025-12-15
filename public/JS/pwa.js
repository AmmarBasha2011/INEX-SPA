/**
 * Registers the service worker for Progressive Web App (PWA) functionality.
 *
 * This script checks if the browser supports service workers. If it does, it waits
 * for the page to fully load and then registers the `service-worker.js` file.
 * This service worker is essential for enabling offline capabilities, caching,
 * and other PWA features.
 *
 * @returns {void}
 */
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/service-worker.js')
      .then(reg => console.log('Service Worker registered:', reg.scope))
      .catch(err => console.error('Service Worker registration failed:', err));
  });
}
/**
 * Handles client-side navigation for the single-page application.
 *
 * This function fetches the content of a new page via an AJAX request and then
 * dynamically updates the current page's content, styles, and scripts. It also
 * manages the browser's history using `pushState` to ensure that the URL reflects
 * the current view and that browser navigation (back/forward) works as expected.
 *
 * @param {string} [route=''] - The route or path of the page to load.
 * @param {string} [requestType='GET'] - The HTTP method to use for the request.
 * @param {string} [dynamic=''] - An optional dynamic segment to be appended to the route,
 *   often used for resource IDs.
 * @returns {void}
 */
function redirect(route='', requestType="GET", dynamic="") {
    let redirecturl = window.WEBSITE_URL + route;
    if (dynamic != "") {
        redirecturl += "/" + dynamic;
    }
    
    // Start with opacity 0 for smooth transition
    document.body.style.opacity = '0';

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Create a temporary container to parse the response
            const tempContainer = document.createElement('div');
            tempContainer.innerHTML = xhr.responseText;
            
            // Collect all style links and create promises for them
            const stylePromises = [];
            const styles = tempContainer.getElementsByTagName('link');
            
            Array.from(styles).forEach(style => {
                if (style.rel === 'stylesheet') {
                    const promise = new Promise((resolve, reject) => {
                        const newStyle = document.createElement('link');
                        newStyle.rel = 'stylesheet';
                        newStyle.href = style.href;
                        newStyle.onload = () => resolve();
                        newStyle.onerror = () => reject();
                        document.head.appendChild(newStyle);
                    });
                    stylePromises.push(promise);
                }
            });

            // Wait for all styles to load
            Promise.all(stylePromises).then(() => {
                // Update the page content
                document.documentElement.innerHTML = xhr.responseText;
                
                // Reload all scripts
                const scripts = document.getElementsByTagName('script');
                for (let script of scripts) {
                    const newScript = document.createElement('script');
                    // Copy all attributes
                    Array.from(script.attributes).forEach(attr => {
                        newScript.setAttribute(attr.name, attr.value);
                    });
                    newScript.textContent = script.textContent;
                    script.parentNode.replaceChild(newScript, script);
                }
                
                // Update URL
                window.history.pushState({}, "", redirecturl);
                
                // Show the content with a smooth transition
                document.body.style.opacity = '1';
                document.body.style.transition = 'opacity 0.3s ease-in';
            }).catch(error => {
                console.error('Error loading styles:', error);
                document.body.style.opacity = '1';
            });
        }
    };
    xhr.open(requestType, redirecturl, true);
    xhr.send();
}

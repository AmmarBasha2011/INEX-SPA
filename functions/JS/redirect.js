function redirect(route, requestType="GET") {
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
                window.history.pushState({}, "", window.WEBSITE_URL + route);
                
                // Show the content with a smooth transition
                document.body.style.opacity = '1';
                document.body.style.transition = 'opacity 0.3s ease-in';
            }).catch(error => {
                console.error('Error loading styles:', error);
                document.body.style.opacity = '1';
            });
        }
    };
    xhr.open(requestType, window.WEBSITE_URL + route, true);
    xhr.send();
}

function redirect(route, requestType="GET") {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Update the HTML
            document.documentElement.innerHTML = xhr.responseText;
            
            // Find and execute all scripts
            const scripts = document.getElementsByTagName('script');
            for (let script of scripts) {
                // Create a fresh script element
                const newScript = document.createElement('script');
                
                // Copy all attributes
                Array.from(script.attributes).forEach(attr => {
                    newScript.setAttribute(attr.name, attr.value);
                });
                
                // Copy the content of the script
                newScript.textContent = script.textContent;
                
                // Replace the old script with the new one
                script.parentNode.replaceChild(newScript, script);
            }
            
            window.history.pushState({}, "", window.WEBSITE_URL + route);
        }
    };
    xhr.open(requestType, window.WEBSITE_URL + route, true);
    xhr.send();
}
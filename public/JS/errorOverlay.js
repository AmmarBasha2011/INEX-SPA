document.addEventListener('DOMContentLoaded', function () {
    const errorDataSource = document.getElementById('phpErrorData');
    if (!errorDataSource || !errorDataSource.textContent) {
        return;
    }

    let errors;
    try {
        errors = JSON.parse(errorDataSource.textContent);
    } catch (e) {
        console.error('Failed to parse PHP error data:', e);
        return;
    }

    if (!errors || errors.length === 0) {
        return;
    }

    // ---- NEW CODE TO LOAD CSS ----
    // Check if CSS is already loaded to prevent multiple additions
    if (!document.getElementById('php-error-overlay-styles')) {
        const head = document.head || document.getElementsByTagName('head')[0];
        const cssLink = document.createElement('link');
        cssLink.id = 'php-error-overlay-styles';
        cssLink.rel = 'stylesheet';
        cssLink.type = 'text/css';
        // Assuming getEnvValue("WEBSITE_URL") is available globally via getWEBSITEURLValue.js
        // or errorOverlay.js is always in public/JS/ and css in public/css/
        // For simplicity, let's construct a relative path or assume WEBSITE_URL is global.
        // If WEBSITE_URL is available as a global JS variable (e.g. window.WEBSITE_URL):
        // cssLink.href = (window.WEBSITE_URL || '') + 'css/errorOverlay.css';
        // If not, and assuming fixed structure public/JS/ and public/css/
        // We might need to get the base URL differently or make it configurable.
        // For INEX SPA, `getWEBSITEURLValue.js` is loaded, which sets `window.WEBSITE_URL`.
        cssLink.href = window.WEBSITE_URL + 'css/errorOverlay.css';
        head.appendChild(cssLink);
        // Alternatively, if getWEBSITEURLValue.js is not guaranteed to have run and set window.WEBSITE_URL
        // when this script runs, a relative path from the HTML's perspective might be needed,
        // or an absolute path if known.
        // Given `getWEBSITEURLValue.js` is loaded in `loadScripts` before other JS,
        // `window.WEBSITE_URL` should be available.
    }
    // ---- END NEW CODE ----

    // For now, display the first error in detail
    const error = errors[0];

    // Create overlay elements
    const overlay = document.createElement('div');
    overlay.id = 'php-error-overlay'; 
    // Basic styling will be in errorOverlay.css, but ensure it's visible for now
    // overlay.style.position = 'fixed'; 
    // overlay.style.top = '0';
    // overlay.style.left = '0';
    // overlay.style.width = '100%';
    // overlay.style.height = '100%';
    // overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
    // overlay.style.zIndex = '99998'; // Ensure it's on top

    const content = document.createElement('div');
    content.id = 'php-error-overlay-content';
    // content.style.position = 'absolute';
    // content.style.top = '50%';
    // content.style.left = '50%';
    // content.style.transform = 'translate(-50%, -50%)';
    // content.style.backgroundColor = '#fff';
    // content.style.padding = '20px';
    // content.style.borderRadius = '5px';
    // content.style.width = '80%';
    // content.style.maxHeight = '80vh';
    // content.style.overflowY = 'auto';

    const closeButton = document.createElement('span');
    closeButton.id = 'php-error-overlay-close';
    closeButton.innerHTML = '&times;';
    // closeButton.style.position = 'absolute';
    // closeButton.style.top = '10px';
    // closeButton.style.right = '15px';
    // closeButton.style.fontSize = '24px';
    // closeButton.style.cursor = 'pointer';
    closeButton.onclick = function () {
        overlay.remove();
    };

    const title = document.createElement('h3');
    title.textContent = error.type === 'Exception' ? `Unhandled ${error.class}` : `PHP ${error.level}`;
    // title.style.color = 'red';

    const message = document.createElement('p');
    message.innerHTML = `<strong>Message:</strong> ${error.message.replace(/\n/g, '<br>')}`;

    const location = document.createElement('p');
    location.innerHTML = `<strong>Location:</strong> ${error.file} on line ${error.line}`;

    content.appendChild(closeButton);
    content.appendChild(title);
    content.appendChild(message);
    content.appendChild(location);

    if (error.trace && error.trace.length > 0) {
        const traceTitle = document.createElement('h4');
        traceTitle.textContent = 'Stack Trace:';
        content.appendChild(traceTitle);

        const traceList = document.createElement('ul');
        traceList.style.listStyleType = 'none';
        traceList.style.paddingLeft = '10px';
        traceList.style.fontFamily = 'monospace';
        traceList.style.fontSize = '12px';

        error.trace.forEach(function (frame, index) {
            const item = document.createElement('li');
            let frameFile = frame.file || '[internal function]';
            let frameLine = frame.line || '-';
            let functionName = '';
            if (frame.class) {
                functionName += frame.class + (frame.type || '::') + frame.function;
            } else if (frame.function) {
                functionName = frame.function;
            }
            item.innerHTML = `#${index} ${frameFile}:${frameLine} - <strong>${functionName}</strong>()`;
            traceList.appendChild(item);
        });
        content.appendChild(traceList);
    }
    
    // If there are more errors, list them in a collapsed section or just count them
    if (errors.length > 1) {
        const moreErrorsTitle = document.createElement('h4');
        moreErrorsTitle.textContent = `${errors.length - 1} more error(s) occurred:`;
        content.appendChild(moreErrorsTitle);
        const otherErrorsList = document.createElement('ul');
        errors.slice(1).forEach(err => {
            const item = document.createElement('li');
            item.textContent = `[${err.level || err.class}] ${err.message.substring(0, 100)}...`;
            otherErrorsList.appendChild(item);
        });
        content.appendChild(otherErrorsList);
    }

    overlay.appendChild(content);
    document.body.appendChild(overlay);

    // Allow Esc key to close overlay
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' || event.keyCode === 27) {
            if (document.getElementById('php-error-overlay')) {
                overlay.remove();
            }
        }
    });
});

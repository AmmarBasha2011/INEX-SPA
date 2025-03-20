window.addEventListener('popstate', function(event) {
    const currentUrl = window.location.href;
    const baseUrl = window.WEBSITE_URL;
    const remaining = currentUrl.replace(baseUrl, '');
    let lastSegment = remaining.split('/').filter(segment => segment).pop() || '';
    redirect(lastSegment);
});
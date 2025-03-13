function redirect(route) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.documentElement.innerHTML = xhr.responseText; // Replace the whole body
            window.history.pushState({}, "", window.WEBSITE_URL + route); // Change the URL without reloading
        }
    };
    xhr.open("GET", window.WEBSITE_URL + route, true);
    xhr.send();
}

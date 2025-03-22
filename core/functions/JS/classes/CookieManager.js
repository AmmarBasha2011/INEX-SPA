class CookieManager {
    static set(name, value, days = 7) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = `${name}=${value}${expires}; path=/`;
    }

    static get(name) {
        let nameEQ = name + "=";
        let cookiesArray = document.cookie.split(';');
        for (let i = 0; i < cookiesArray.length; i++) {
            let cookie = cookiesArray[i].trim();
            if (cookie.indexOf(nameEQ) === 0) {
                return cookie.substring(nameEQ.length);
            }
        }
        return null;
    }

    static delete(name) {
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    }

    static exists(name) {
        return this.get(name) !== null;
    }

    static getAll() {
        let cookies = {};
        let cookiesArray = document.cookie.split(';');
        cookiesArray.forEach(cookie => {
            let [key, value] = cookie.split('=');
            cookies[key.trim()] = value;
        });
        return cookies;
    }
}

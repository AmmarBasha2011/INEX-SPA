/**
 * A utility class for managing browser cookies with a simple, static interface.
 *
 * This class provides a consistent and easy-to-use API for setting, getting,
 * deleting, and checking for the existence of cookies.
 */
class CookieManager {
    /**
     * Sets or updates a cookie with a specified name, value, and expiration.
     *
     * @param {string} name - The name of the cookie.
     * @param {string} value - The value to store in the cookie.
     * @param {number} [days=7] - The number of days until the cookie expires.
     * @returns {void}
     */
    static set(name, value, days = 7) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = `${name}=${value}${expires}; path=/`;
    }

    /**
     * Retrieves the value of a cookie by its name.
     *
     * @param {string} name - The name of the cookie to retrieve.
     * @returns {string|null} The value of the cookie, or null if it is not found.
     */
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

    /**
     * Deletes a cookie by its name.
     *
     * This is achieved by setting the cookie's expiration date to a time in the past.
     *
     * @param {string} name - The name of the cookie to delete.
     * @returns {void}
     */
    static delete(name) {
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    }

    /**
     * Checks if a cookie with the specified name exists.
     *
     * @param {string} name - The name of the cookie to check.
     * @returns {boolean} True if the cookie exists, false otherwise.
     */
    static exists(name) {
        return this.get(name) !== null;
    }

    /**
     * Retrieves all cookies as an object.
     *
     * @returns {Object<string, string>} An object where keys are cookie names and values are their corresponding values.
     */
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

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
        // SECURITY: Validate cookie name — only alphanumeric, dash, underscore
        if (!/^[a-zA-Z0-9_-]+$/.test(name)) {
            console.error('CookieManager: Invalid cookie name.');
            return;
        }
        // SECURITY: Validate days is numeric
        if (typeof days !== 'number' || days < 0 || days > 365) {
            days = 7;
        }
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        // SECURITY: Encode value to prevent cookie injection
        document.cookie = `${encodeURIComponent(name)}=${encodeURIComponent(value)}${expires}; path=/; SameSite=Strict`;
    }

    /**
     * Retrieves the value of a cookie by its name.
     *
     * @param {string} name - The name of the cookie to retrieve.
     * @returns {string|null} The value of the cookie, or null if it is not found.
     */
    static get(name) {
        // SECURITY: Validate cookie name
        if (!/^[a-zA-Z0-9_-]+$/.test(name)) {
            return null;
        }
        let nameEQ = encodeURIComponent(name) + "=";
        let cookiesArray = document.cookie.split(';');
        for (let i = 0; i < cookiesArray.length; i++) {
            let cookie = cookiesArray[i].trim();
            if (cookie.indexOf(nameEQ) === 0) {
                return decodeURIComponent(cookie.substring(nameEQ.length));
            }
        }
        return null;
    }

    /**
     * Deletes a cookie by its name.
     *
     * @param {string} name - The name of the cookie to delete.
     * @returns {void}
     */
    static delete(name) {
        // SECURITY: Validate cookie name
        if (!/^[a-zA-Z0-9_-]+$/.test(name)) {
            return;
        }
        document.cookie = `${encodeURIComponent(name)}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; SameSite=Strict`;
    }

    /**
     * Checks if a cookie exists.
     *
     * @param {string} name - The name of the cookie to check.
     * @returns {boolean} True if the cookie exists.
     */
    static exists(name) {
        // SECURITY: Validate cookie name
        if (!/^[a-zA-Z0-9_-]+$/.test(name)) {
            return false;
        }
        let nameEQ = encodeURIComponent(name) + "=";
        return document.cookie.split(';').some(cookie => cookie.trim().startsWith(nameEQ));
    }

    /**
     * Gets all cookies as an object.
     *
     * @returns {Object} An object with all cookies.
     */
    static getAll() {
        let cookies = {};
        document.cookie.split(';').forEach(cookie => {
            let [name, ...rest] = cookie.trim().split('=');
            cookies[decodeURIComponent(name)] = decodeURIComponent(rest.join('='));
        });
        return cookies;
    }
}
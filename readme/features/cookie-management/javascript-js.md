# JavaScript (JS)

You can manage cookies more easily by:

```javascript
// Create cookie
CookieManager.set("username", "Ammar", 7);
CookieManager.set("email", "ammar@example.com", 7);

// Get cookie value
console.log(CookieManager.get("username")); // Ammar
console.log(CookieManager.get("email"));    // ammar@example.com

// Edit cookie
CookieManager.set("username", "INEX SPA", 7);

// Get cookie value
console.log(CookieManager.get("username")); // INEX SPA

// Exists cookie
console.log(CookieManager.exists("email")); // true
console.log(CookieManager.exists("password")); // false

// Delete cookie
CookieManager.delete("username");

// Get cookie value
console.log(CookieManager.get("username")); // null

// Get all cookies
console.log(CookieManager.getAll());
```

# PHP

You can manage cookies more easily by:

```php
<?php
// Create cookie
CookieManager::set("username", "Ammar", 7);
CookieManager::set("email", "ammar@example.com", 7);

// Get cookie value
echo CookieManager::get("username"); // Ammar
echo "<br>";
echo CookieManager::get("email"); // ammar@example.com

// Edit cookie
CookieManager::set("username", "INEX SPA", 7);

// Get cookie value
echo "<br>" . CookieManager::get("username"); // INEX SPA

// Exists cookie
echo "<br>" . (CookieManager::exists("email") ? "Email exists" : "Email not found");
echo "<br>" . (CookieManager::exists("password") ? "Password exists" : "Password not found");

// Delete cookie
CookieManager::delete("username");

// Get cookie value
echo "<br>" . (CookieManager::get("username") ?? "Username not found");

// Get all cookies
echo "<pre>";
print_r(CookieManager::getAll());
echo "</pre>";

```

# Security

**INEX SPA - Security System Documentation**

***

#### 🛡️ Feature: Security System

The Security system in INEX SPA is designed to help developers easily protect their apps from common vulnerabilities. It provides essential functions to manually validate, clean, and verify user input and behavior.

***

#### 🛠️ Enable Security System

To enable the Security system, open your `.env` file and set:

```ini
USE_SECURITY=true
```

Once enabled, you can use the `Security` class manually in your PHP files.

***

#### 📁 Files and Structure

* **Security Class**: `/core/functions/PHP/classes/Security.php`

***

#### ⚙️ Security Functions

**➤ `Security::sanitizeInput($string)`**

Cleans a string from common XSS (Cross-site Scripting) attacks.

**Example:**

```php
$userInput = '<script>alert("XSS")</script>';
echo Security::sanitizeInput($userInput); // Outputs: 
```

This function removes or neutralizes harmful tags and scripts.

***

**➤ `Security::validateAndSanitize($input, $type)`**

Validates and sanitizes based on the type provided.

* `$type` currently supports only `'xss'`

**Example:**

```php
$cleanInput = Security::validateAndSanitize($_POST['comment'], 'xss');
```

***

#### ✅ Use Cases

* Cleaning user input before displaying it.
* Preventing script injection in forms, URLs, comments, etc.
* Adding a layer of safety when rendering dynamic content.

***

#### 🔒 Notes

* This system only runs if `USE_SECURITY=true` is set in `.env`.
* Designed to be used manually where needed.
* Does not interfere with data unless you call it.
* Currently supports only **XSS protection**.

> Future updates may include CSRF token validation, header checks, and file upload protection.

***

#### ✅ Status

✅ Lightweight\
✅ Easy to configure\
✅ Extensible\
✅ No external libraries

***

© INEX SPA Team - Security System

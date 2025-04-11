# Logger

**INEX SPA - Logger System Documentation**

***

#### 📝 Feature: Logger System

INEX SPA includes a lightweight, powerful logging system that allows developers to log important events, errors, and security-related actions. It helps in debugging, monitoring, and maintaining the integrity of your application.

***

#### 🛠️ Enable Logging

To enable the Logger system, open your `.env` file and set the following:

```ini
USE_LOGGING=true
```

Once enabled, you can use the Logger manually in your PHP code.

***

#### 📁 Files and Structure

* **Logger Class**: `/core/functions/PHP/classes/Logger.php`
* **Logs Directory**: `/core/logs/`
  * `system.log`
  * `errors.log`
  * `security.log`
  * `api.log`

***

#### ⚙️ Logger Functions

**➤ `Logger::log($type, $message)`**

Logs a message with a specific type.

* `$type`: One of the following:
  * `system`: For general events.
  * `error`: For errors and exceptions.
  * `security`: For unauthorized access, login attempts, etc.
  * `api`: For tracking API calls or integrations.

**Example:**

```php
Logger::log('system', 'Page loaded successfully');
Logger::log('error', '404 Page Not Found');
Logger::log('security', 'Unauthorized IP tried to access admin panel');
Logger::log('api', 'Webhook received from Discord');
```

***

**➤ `Logger::clearLogs()`**

Clears the content of all log files.

**Example:**

```php
Logger::clearLogs();
Logger::log('system', 'All logs have been cleared.');
```

> 🔒 You can restrict access to `clearLogs()` usage in production.

***

#### 📄 Log Format

Each entry is recorded like this:

```
[YYYY-MM-DD HH:MM:SS] [type] Message
```

**Example:**

```
[2025-04-11 21:00:22] [error] Route Not Found: /api/user
```

***

#### ✅ Status

✅ Lightweight\
✅ Easy to configure\
✅ Extensible\
✅ No external libraries

***

© INEX SPA Team - Logger System

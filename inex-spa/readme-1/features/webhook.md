# Webhook

**INEX SPA - Webhook System Documentation**

***

#### 🌐 Feature: Webhooks

The Webhook system allows your INEX SPA application to send HTTP requests (webhooks) to external services or internal APIs whenever certain actions occur. This can be used for integrations, notifications, and automation.

***

#### ⚙️ Enable Webhooks

To enable this feature, open your `.env` file and set:

```ini
USE_WEBHOOK=true
```

Once enabled, the `Webhook` class will be available to use manually.

***

#### 📂 Files & Location

* **Webhook Class**: `/core/functions/PHP/classes/Webhook.php`

***

#### 🧠 How It Works

You can call `Webhook::send()` to trigger any custom webhook manually.

***

#### 🔧 Function: `Webhook::send($url, $data = [])`

**Parameters:**

* `$url` _(string)_ – Webhook URL to send to.
* `$data` _(array)_ – Optional. Data to send in the request body (as JSON).

**Example:**

```php
Webhook::send(
    'https://example.com/hook',
    [
        'event' => 'user_registered',
        'user_id' => 12
    ]
);
```

***

#### ✅ Notes

* Uses `file_get_contents` with stream context (no cURL or library).
* Supports any backend accepting JSON payload.
* Lightweight and synchronous.

***

© INEX SPA Team - Webhook System

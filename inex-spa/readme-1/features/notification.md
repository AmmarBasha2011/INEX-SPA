# Notification

**INEX SPA - Notifications System Documentation**

***

#### 🔔 Feature: In-Site Notifications

The Notifications system in INEX SPA allows developers to show beautiful, animated messages inside any part of the page. It supports multiple positions, types, and stacking notifications dynamically.

***

#### ⚙️ Enable Notifications

To enable this feature, open your `.env` file and set:

```ini
USE_NOTIFICATION=true
```

Once enabled, the JavaScript function `showNotification()` will be available globally.

***

#### 📂 Files & Location

* **Notification Script**: `/public/JS/showNotification.js`
* **Stylesheet**: Inline or dynamically injected

***

#### 🔧 Function: `showNotification(message, position = 'bottom-right', duration = 3000, type = 'primary')`

**Parameters:**

* `message` _(string)_ – The message to show.
* `position` _(string)_ – Optional. One of:
  * `bottom-right`, `bottom-left`, `top-right`, `top-left`
* `duration` _(number)_ – Optional. Time in milliseconds before the notification auto-hides. Default is `3000` ms.
* `type` _(string)_ – Optional. One of:
  * `success`, `info`, `danger`, `primary`, `secondary`

**Example:**

```html
<script>
  showNotification("Saved successfully!");
  showNotification("Custom style!", "top-left", 7000, "danger");
</script>
```

***

#### ✨ Features

* Hyper animated gradients
* Smooth entrance and exit animations
* Multiple notifications stack at once
* Auto hide after a given time
* Manual hide via `*` button

***

#### 🎨 Styling

Each `type` changes the background styling to match the purpose of the message. Positions are aligned with fixed CSS and animated using fade-in/out effects.

***

#### ❗ Notes

* No library required
* Automatically injects styles
* Customizable via the script file
* Works even without a framework

***

#### ✅ Status

✅ Lightweight\
✅ Easy to configure\
✅ Extensible\
✅ No external libraries

***

© INEX SPA Team - Notification System

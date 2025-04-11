# Firewall

**INEX SPA - Firewall System Documentation**

***

#### 🔐 Feature: Firewall Protection

The Firewall system in INEX SPA helps protect your application from malicious access, unwanted bots, and rate abuse. It is a lightweight, file-based rule checker that runs before the main application loads.

***

#### 🛠️ Enable the Firewall

To enable the Firewall system, open your `.env` file and set the following:

```ini
USE_FIREWALL=true
```

Once enabled, you can use the Firewall system manually by calling the Firewall class directly.

***

#### 📁 Files and Structure

* **Firewall Rules File**: `/Json/firewall.json`
* **Firewall Class**: `/core/functions/PHP/classes/Firewall.php`

***

#### ⚙️ How It Works

1. INEX SPA loads the rules from `/Json/firewall.json`.
2. You can manually check requests using the static method:

```php
Firewall::check();
```

3. The function compares the visitor's IP or user-agent against the rule list.
4. If a match is found, the request is blocked with a 403 error.

***

#### 📄 firewall.json Example

```json
{
  "block_ips": [
    "192.168.1.10",
    "10.0.0.1"
  ],
  "block_user_agents": [
    "curl",
    "BadBot"
  ],
  "redirect_blocked_to": "blocked"
}
```

* `block_ips`: IP addresses to block.
* `block_user_agents`: Partial or full matches of bad user-agents.
* `redirect_blocked_to`: Page will redirect if blocked

> 💡 Future versions may include rate limiting and temporary bans.

***

#### ❗ Important Notes

* The firewall only runs if `USE_FIREWALL=true` is set in `.env`.
* Country blocking uses a simple IP-to-country detection. Make sure the IP location file is available if needed.
* For full control, you can extend the `Firewall.php` class.

***

#### ✅ Status

✅ Lightweight\
✅ Easy to configure\
✅ Extensible\
✅ No external libraries

***

© INEX SPA Team - Firewall System

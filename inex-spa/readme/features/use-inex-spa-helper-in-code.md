---
hidden: true
---

# Use INEX SPA Helper in code

Now, you can chat with `INEX SPA Helper` but by code:

* First, update `.env`:

```ini
GEMINI_API_KEY=
GEMINI_MODEL_ID=gemini-2.0-flash
GEMINI_ENDPOINT=https://generativelanguage.googleapis.com/v1beta/models/
```

* Second, in any part in the application:

```php
<?php
$inexspahelper = json_decode(inexSpaHelper(`question`), true);
print_r($inexspahelper);
?>
```

*
  * if: return success=true, return message. else: return success=false, return error:

```php
<?php
if ($inexspahelper['success'] == true) {
    echo "Response: " . $inexspahelper['message'];
} else {
    echo "Error: " . $inexspahelper['error'];
}
?>
```

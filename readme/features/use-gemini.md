# Use Gemini

Now, you can chat with `Gemini` but by code:

* First, update `.env`:

```ini
GEMINI_API_KEY=
GEMINI_MODEL_ID=gemini-2.0-flash
GEMINI_ENDPOINT=https://generativelanguage.googleapis.com/v1beta/models/
```

* Second, in any part in the application:

```php
<?php
$UseGemini = json_decode(useGemini(`question`, `knowledge`="", `instrcutions`="", `temperature`=0.7, `topK`=40, `topP`=0.95, `maxOutPutTokens`=2048), true);
print_r($UseGemini);
?>
```

*
  * if: return success=true, return message. else: return success=false, return error:

```php
<?php
if ($UseGemini['success'] == true) {
    echo "Response: " . $UseGemini['message'];
} else {
    echo "Error: " . $UseGemini['error'];
}
?>
```

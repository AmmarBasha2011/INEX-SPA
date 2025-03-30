# @useGemini - AI-Powered Assistance

The `@useGemini` directive in Ahmed PHP Template Engine allows you to integrate AI-powered content generation using Google's Gemini AI.

### Syntax

```php
@useGemini(prompt)
```

* `prompt`: The text prompt to send to Gemini AI for content generation.

### Description

* This directive queries Gemini AI to generate dynamic content.
* Useful for text generation, suggestions, and AI-driven functionality.

### Example

```php
@var(response, @useGemini("Generate a blog post about PHP performance optimization."))
<p>@response</p>
```

### Expected Output

```html
<p>PHP performance optimization involves caching, opcode compilation, and minimizing database queries...</p>
```

### Notes

* Requires an active connection to Google's Gemini AI.
* Can be used for real-time AI-driven content suggestions.
* Output depends on the given prompt and AI processing.

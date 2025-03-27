# @getLang

The `@getLang` directive in Ahmed Template Engine for INEX SPA is used to retrieve language-specific text from localization files, making your application multilingual.

### Syntax

```php
@getLang('key')
```

#### Example:

```php
<p>@getLang('welcome_message')</p>
```

If the `welcome_message` key exists in the localization file, it will be replaced with its corresponding translated value.

### Switching Languages Dynamically

You can change the current language dynamically using:

```php
setLanguage('fr'); // Switch to French
```

Then `@getLang('welcome_message')` will return the French translation if available.

### Conclusion

The `@getLang` directive simplifies multilingual support in Ahmed Template Engine, ensuring dynamic and flexible language rendering in your application.

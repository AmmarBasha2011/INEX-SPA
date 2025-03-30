# @setLang - Set Active Language

The `@setLang` directive in Ahmed PHP Template Engine allows you to change the active language dynamically within the template.

### Syntax

```php
@setLang(languageCode)
```

* `languageCode`: The language identifier (e.g., `en`, `fr`, `ar`).

### Description

* This directive sets the active language, affecting how translations are handled.
* Used when switching between multiple language files.
* Works in combination with `@lang` to retrieve translated strings.

### Example

```php
@setLang("fr")

<p>@lang("welcome.message")</p>
```

### Notes

* Ensure that the language files are properly configured.
* Affects all subsequent `@lang` calls within the template.
* Typically used in multilingual applications to dynamically switch languages.

# isMinTextLength

`Validation` class provide `isMinTextLength` function

## Syntax

```php
Validation::isMinTextLength("text", "minLength")
```

## Example

<pre class="language-php"><code class="lang-php"><strong>echo Validation::isMinTextLength("Ammar", 10) // false
</strong><strong>echo Validation::isMinTextLength("Ammar", 1) // true
</strong></code></pre>

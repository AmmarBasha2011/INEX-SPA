# @require - Include PHP Files

The `@require` directive in Ahmed PHP Template Engine allows you to include an external PHP file within your template.

### Syntax

```php
@require(filePath)
```

* `filePath`: The path to the PHP file relative to the project directory.

### Description

* This directive loads and executes a PHP file within the template.
* Useful for reusing code, importing functions, or loading configuration files.
* If the file is missing, an error will be triggered.

### Example

Assuming `header.php` contains:

```php
<h1>Welcome to Ahmed Engine</h1>
```

You can use:

```php
@require("header.php")

<p>Main content goes here.</p>
```

### Expected Output

```html
<h1>Welcome to Ahmed Engine</h1>
<p>Main content goes here.</p>
```

### Notes

* The included file is executed in the current template scope.
* Ensure the file exists to avoid errors.
* Unlike `@include`, `@require` will stop execution if the file is not found.

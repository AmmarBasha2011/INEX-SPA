# @include - Including Other Templates

The `@include` directive in Ahmed PHP Template Engine allows you to insert the contents of another template file within the current template. This helps in reusing common parts of your UI, such as headers, footers, and sidebars, without duplicating code.

### Syntax

```php
@include('filename')
```

* `filename` should be the name of the template file without the extension.
* The file must exist in the templates directory.

### Example

Assume you have a header file named `header.ahmed.php` and you want to include it in your main template:

**header.ahmed.php**

```html
<header>
    <h1>Welcome to My Website</h1>
</header>
```

**main.ahmed.php**

```php
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    @include('header')
    <p>This is the main content of the page.</p>
</body>
</html>
```

### Output

```html
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <header>
        <h1>Welcome to My Website</h1>
    </header>
    <p>This is the main content of the page.</p>
</body>
</html>
```

### Notes

* If the included file does not exist, an error will be thrown.
* You can include multiple files within a single template.
* Avoid circular includes to prevent infinite loops.

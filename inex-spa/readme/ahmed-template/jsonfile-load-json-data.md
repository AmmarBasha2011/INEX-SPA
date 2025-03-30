# @jsonFile - Load JSON Data

The `@jsonFile` directive in Ahmed PHP Template Engine allows you to load and parse a JSON file directly within your template.

### Syntax

```php
@jsonFile(filePath)
```

* `filePath`: The path to the JSON file relative to the project directory.

### Description

* This directive reads a JSON file and converts it into an associative array.
* Allows accessing JSON data directly in the template without additional PHP code.
* Useful for handling configuration files, translations, or structured data.

### Example

Assuming `data.json` contains:

```json
{
    "site": "Ahmed Engine",
    "year": 2025
}
```

You can use:

```php
@jsonFile("data.json")

<h1>@site</h1>
<p>© @year All rights reserved.</p>
```

### Expected Output

```html
<h1>Ahmed Engine</h1>
<p>© 2025 All rights reserved.</p>
```

### Notes

* Ensure the JSON file is properly formatted to avoid parsing errors.
* Nested JSON properties can be accessed using dot notation (e.g., `@data.key`).
* If the file does not exist or contains invalid JSON, an error may occur.

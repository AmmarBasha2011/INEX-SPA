# Autoloading Framework Components

The framework implements an advanced autoloading system that automatically manages class and function dependencies. This means:

* Don't use `require_once`, `require`, `include_once` or `include` for framework files
* All framework classes and functions are automatically available
* The autoloader handles dependency resolution efficiently
* Proper namespacing ensures no conflicts between components
* Framework core files are loaded in optimal order
* Better performance by avoiding redundant file inclusions

Example of correct usage:

```php
// Correct - Direct usage
$result = executeStatement("SELECT * FROM users");

// Incorrect - Don't do this
require_once 'functions/PHP/classes/Database.php';
$result = executeStatement("SELECT * FROM users");
```

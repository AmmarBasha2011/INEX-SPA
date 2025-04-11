# Create init.php file

To create init.php file, create in a root, this is required. and create like this:

```php
<?php

# init.php
require_once 'libraryName/filename';
require_once 'libraryName/filename';
```

Still to add all functions and classes, and you can use foreach loop for files:

```php
<?php

# init.php
$files = glob(__DIR__ . '/*.php');

foreach ($files as $file) {
    if ($file !== __FILE__) {
        require_once $file;
    }
}
```

Or you can use for folders and subfolders:

```php
<?php

function requireAllPHPFiles($dir) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    
    foreach ($iterator as $file) {
        if ($file->isFile() && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            if ($file->getRealPath() !== __FILE__) {self
                require_once $file->getRealPath();
            }
        }
    }
}

requireAllPHPFiles(__DIR__);
```

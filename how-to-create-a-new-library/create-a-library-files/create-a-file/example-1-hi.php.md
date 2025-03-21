# Example 1: hi.php

```php
<?php

class Hi{
    public $name;
    
    // Constructor (optional)
    public function __construct($name) {
        $this->name = $name;
    }

    // Method
    public function sayHello() {
        return "Hello, " . $this->name . "!";
    }
}
?>
```

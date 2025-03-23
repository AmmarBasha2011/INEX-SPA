# Example 2: useFramework.php

```php
<?php

class UseFramework {
    private $lang = 'en';

    // Constructor
    public function __construct($lang = 'en') { // Default value in case no parameter is provided
        $this->lang = $lang;
    }

    // Set language
    public function setLanguage() {
        if (class_exists('Language')) { // Check if Language class exists
            Language::setLanguage($this->lang);
        } else {
            throw new Exception("Error: Language class not found.");
        }
    }
}
?>
```

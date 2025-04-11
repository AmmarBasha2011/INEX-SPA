# Layout Feature

INEX SPA PHP Framework introduces a powerful **Layout** feature that helps you eliminate code duplication (DRY - Don't Repeat Yourself) and structure your application efficiently. By using layouts, you can define a consistent structure across multiple pages with minimal code repetition.

#### **How to Use Layouts in INEX SPA**

**Step 1: Create a Layout File**

Layouts reside in the `layouts/` folder. These files define the reusable structure of your application pages.

Create a new layout file at `layouts/main.ahmed.php`:

```php
// layouts/main.php
<title><?= $title; ?></title>
<body>
    <?= Layout::section('content'); ?>
</body>
```

This file serves as the main template where dynamic content will be injected.

***

**Step 2: Create a Route Using the Layout**

To use the layout, create a route file inside the `web/` folder:

```php
// web/index.php
<?php
Layout::start('content');
?>
    <h1>Hi, From INEX SPA</h1>
<?php
Layout::end();
```

Here, `Layout::start('content')` defines the section where dynamic content will be placed, and `Layout::end()` marks the end of that section.

***

**Step 3: Render the Page with the Layout**

Finally, complete the route file by rendering the layout:

```php
Layout::render('main', 'index', ['title' => 'INEX SPA']);
```

This renders the `index.ahmed.php` content inside `layouts/main.ahmed.php`, passing variables like `$title` dynamically.

***

#### **Conclusion**

That's it! You've successfully implemented layouts in INEX SPA PHP Framework. This feature significantly reduces redundant code and keeps your application organized. Happy coding!

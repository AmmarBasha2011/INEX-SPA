<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Component System Example</title>
</head>
<body>
    <h1>Component System Example</h1>

    <h2>Button Component</h2>
    <?php echo component('Button', ['text' => 'Click Me!']); ?>

    <h2>Test Component</h2>
    <?php echo component('Test', ['text' => 'This is a test component.']); ?>
</body>
</html>

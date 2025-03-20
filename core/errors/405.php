<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>405 Method Not Allowed</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>405</h1>
    <p>Oops! The method you're using is not allowed on this page. Please check your request and try again.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

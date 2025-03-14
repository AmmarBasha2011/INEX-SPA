<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>400 Bad Request</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>400</h1>
    <p>Oops! The server could not understand your request due to malformed syntax.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

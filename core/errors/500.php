<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 Internal Server Error</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>500</h1>
    <p>Oops! Internal Server Error. Something went wrong on our end.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

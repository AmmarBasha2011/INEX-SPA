<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>502 Bad Gateway</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>502</h1>
    <p>Oops! The server received an invalid response from an upstream server.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

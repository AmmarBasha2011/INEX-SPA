<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>406 Not Acceptable</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>406</h1>
    <p>Not Acceptable! The requested resource cannot generate content according to the Accept headers sent in the request.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

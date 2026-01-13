<?php
/**
 * Renders the 414 URI Too Long error page.
 *
 * This script is displayed when the URI (Uniform Resource Identifier)
 * requested by the client is longer than the server is configured to handle.
 * This can happen with overly complex GET requests that encode too much
 * data into the URL.
 *
 * @var string $WEBSITE_URL The base URL of the website, used for linking
 *                          to CSS files and the homepage.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>414 URI Too Long</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>414</h1>
    <p>Oops! The URI requested is too long for the server to process.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

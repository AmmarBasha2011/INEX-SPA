<?php
/**
 * Renders the HTTP 414 URI Too Long error page.
 *
 * This page is shown when the URI (Uniform Resource Identifier) provided in the
 * request is longer than the server is willing to interpret. It informs the user
 * of the issue and includes a link to the homepage.
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

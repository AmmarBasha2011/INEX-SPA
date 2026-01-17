<?php
/**
 * Renders the HTTP 405 Method Not Allowed error page.
 *
 * This page is displayed when a request is made to a resource using an HTTP method
 * that it does not support. For example, using GET on a form that requires POST.
 * It informs the user about the method mismatch and provides a link to return to
 * the homepage.
 */
?>
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

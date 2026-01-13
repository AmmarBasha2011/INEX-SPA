<?php
/**
 * Renders the 405 Method Not Allowed error page.
 *
 * This script is displayed when a request is made to a resource using an HTTP
 * method that is not supported by that resource. For example, using GET on a
 * form that requires a POST request. The page informs the user of the issue
 * and provides a way to return to the homepage.
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
    <title>405 Method Not Allowed</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>405</h1>
    <p>Oops! The method you're using is not allowed on this page. Please check your request and try again.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

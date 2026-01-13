<?php
/**
 * Renders the 500 Internal Server Error page.
 *
 * This script is displayed as a generic catch-all error page when the server
 * encounters an unexpected condition that prevents it from fulfilling the
 * request. It provides a non-technical message to the user, indicating that
 * the issue is on the server side.
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
    <title>500 Internal Server Error</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>500</h1>
    <p>Oops! Internal Server Error. Something went wrong on our end.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

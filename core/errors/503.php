<?php
/**
 * Renders the 503 Service Unavailable error page.
 *
 * This script is displayed when the server is temporarily unable to handle the
 * request due to maintenance, overload, or other temporary conditions. It
 * informs the user that the service is down for a short period.
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
    <title>503 Service Unavailable</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>503</h1>
    <p>Sorry! The server is temporarily unavailable due to maintenance or overload.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

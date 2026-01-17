<?php
/**
 * Renders the HTTP 404 Not Found error page.
 *
 * This is the standard page shown when a user tries to access a URL that does not
 * correspond to any resource on the server. It indicates that the server is reachable,
 * but the specific page requested could not be located. The page includes a helpful
 * message and a button to return to the homepage.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>404</h1>
    <p>Oops! The page you're looking for could not be found.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

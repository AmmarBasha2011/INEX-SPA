<?php
/**
 * Renders the 504 Gateway Timeout error page.
 *
 * This script is displayed when the server, acting as a gateway or proxy,
 * did not receive a timely response from an upstream server. This indicates
 * a network timeout between servers, not between the client and the server.
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
    <title>504 Gateway Timeout</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>504</h1>
    <p>Oops! The server timed out while waiting for a response from another server.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

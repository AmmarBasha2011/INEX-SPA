<?php
/**
 * Renders the 502 Bad Gateway error page.
 *
 * This script is displayed when the server, while acting as a gateway or proxy,
 * received an invalid response from an upstream server it accessed to fulfill
 * the request. It indicates a problem in the server-to-server communication chain.
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
    <title>502 Bad Gateway</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>502</h1>
    <p>Oops! The server received an invalid response from an upstream server.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

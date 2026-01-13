<?php
/**
 * Renders the 415 Unsupported Media Type error page.
 *
 * This script is displayed when the server rejects a request because the
 * payload is in a format that is not supported by the target resource for
 * the requested method. For example, sending an XML payload to an endpoint
 * that only accepts JSON.
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
    <title>415 Unsupported Media Type</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>415</h1>
    <p>Oops! The server cannot process the media type of the requested data.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>
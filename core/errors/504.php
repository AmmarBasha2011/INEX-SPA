<?php
/**
 * Renders the HTTP 504 Gateway Timeout error page.
 *
 * This page is shown when the server, acting as a gateway or proxy, did not receive
 * a timely response from an upstream server. It informs the user about the timeout
 * and includes a link to navigate back to the homepage.
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

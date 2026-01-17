<?php
/**
 * Renders the HTTP 415 Unsupported Media Type error page.
 *
 * This page is displayed when the server refuses to accept a request because the
 * payload format is in an unsupported media type. It informs the user that the
 * server cannot process the content type of their request and provides a link back
 * to the homepage.
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

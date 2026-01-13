<?php
/**
 * Renders the 412 Precondition Failed error page.
 *
 * This script is displayed when one or more conditions specified in the
 * request headers (e.g., `If-Match`, `If-Unmodified-Since`) evaluate to false
 * on the server. It indicates that the server is unable to process the
 * request because a client-side precondition has not been met.
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
    <title>412 Precondition Failed</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>412</h1>
    <p>Precondition Failed: The server cannot meet the preconditions in the request headers.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

<?php
/**
 * Renders the 400 Bad Request error page.
 *
 * This script displays a user-friendly error page when the server cannot
 * process a request due to a client-side error, such as malformed request
 * syntax, invalid request message framing, or deceptive request routing.
 * It provides a clear error message and a button to navigate back to the
 * homepage, enhancing the user experience during unexpected errors.
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
    <title>400 Bad Request</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>400</h1>
    <p>Oops! The server could not understand your request due to malformed syntax.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

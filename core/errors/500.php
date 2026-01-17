<?php
/**
 * Renders the HTTP 500 Internal Server Error page.
 *
 * This page is a generic catch-all for unexpected server-side errors that prevent
 * the fulfillment of a request. It indicates a problem with the server's
 * configuration or application logic, rather than an issue with the client's
 * request. The page provides a non-technical message and a link to the homepage.
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

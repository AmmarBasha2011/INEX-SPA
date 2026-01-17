<?php
/**
 * Renders the HTTP 412 Precondition Failed error page.
 *
 * This page is shown when one or more conditions given in the request header fields
 * evaluated to `false` when tested on the server. It indicates that the client's
 * preconditions for the request were not met, and includes a link to the homepage.
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

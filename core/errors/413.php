<?php
/**
 * Renders the HTTP 413 Request Entity Too Large error page.
 *
 * This page is displayed when the request entity is larger than the limits defined
 * by the server. It informs the user that their request (e.g., a file upload) is
 * too large and cannot be processed, providing a link back to the homepage.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>413 Request Entity Too Large</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>413</h1>
    <p>Oops! The file or data you're trying to upload is too large for the server to process.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

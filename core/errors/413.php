<?php
/**
 * Renders the 413 Request Entity Too Large error page.
 *
 * This script is displayed when the request entity is larger than the server
 * is willing or able to process. This typically occurs during file uploads
 * when the file size exceeds the server's configured limits.
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
    <title>413 Request Entity Too Large</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>413</h1>
    <p>Oops! The file or data you're trying to upload is too large for the server to process.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

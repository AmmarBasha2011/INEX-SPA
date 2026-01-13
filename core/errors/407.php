<?php
/**
 * Renders the 407 Proxy Authentication Required error page.
 *
 * This script is displayed when the client must first authenticate itself
 * with a proxy server. This error is sent with a `Proxy-Authenticate` header
 * containing a challenge on how to authenticate. The page informs the user
 * of this requirement.
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
    <title>407 Proxy Authentication Required</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>407</h1>
    <p>Proxy Authentication Required. You must authenticate with a proxy server before this request can be served.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

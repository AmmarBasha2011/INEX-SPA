<?php
/**
 * Renders the 410 Gone error page.
 *
 * This script is displayed when the requested resource is no longer available
 * at the origin server and this condition is likely to be permanent. It informs
 * the user that the content has been intentionally removed and will not be
 * available again.
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
    <title>410 Gone</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>410</h1>
    <p>Gone! The requested resource is no longer available and will not be available again.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

<?php
/**
 * Renders the HTTP 410 Gone error page.
 *
 * This page indicates that the target resource is no longer available at the
 * origin server and that this condition is likely to be permanent. It is used to
 * inform clients that they should remove their links to this resource. The page
 * provides a clear message and a link to the homepage.
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

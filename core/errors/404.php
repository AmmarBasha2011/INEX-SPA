<?php
/**
 * Renders the 404 Not Found error page.
 *
 * This script is displayed when a user tries to access a URL that does not
 * correspond to any existing resource on the server. It provides a standard
 * "Not Found" message and a link to return to the homepage, guiding the user
 * back to a valid part of the site.
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
    <title>404 Not Found</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>404</h1>
    <p>Oops! The page you're looking for could not be found.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

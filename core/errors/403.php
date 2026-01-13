<?php
/**
 * Renders the 403 Forbidden error page.
 *
 * This script is displayed when a user attempts to access a resource that
 * they are not authorized to view, even if they are authenticated. It indicates
 * that the server understood the request but refuses to fulfill it. The page
 * provides a clear error message and a link back to the homepage.
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
    <title>403 Forbidden</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>403</h1>
    <p>Access Forbidden. You don't have permission to access this resource.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

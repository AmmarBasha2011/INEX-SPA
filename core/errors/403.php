<?php
/**
 * Renders the HTTP 403 Forbidden error page.
 *
 * This page is displayed when the server understands the request but refuses to
 * authorize it. This may be due to insufficient permissions, even if the user is
 * authenticated. The page informs the user that they are not allowed to access
 * the resource and provides a link to the homepage.
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

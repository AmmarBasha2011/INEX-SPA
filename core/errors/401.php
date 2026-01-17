<?php
/**
 * Renders the HTTP 401 Unauthorized error page.
 *
 * This page is displayed when a request requires user authentication, but the
 * client has not yet provided valid credentials. It informs the user that they
 * need to authenticate to access the requested resource and includes a link to
 * return to the homepage.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 Unauthorized</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>401</h1>
    <p>Unauthorized! You don't have permission to access this resource.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

<?php
/**
 * Renders the 409 Conflict error page.
 *
 * This script is displayed when a request could not be completed due to a
 * conflict with the current state of the target resource. This can occur,
 * for example, when two clients try to edit the same resource simultaneously.
 * The page informs the user about this conflict.
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
    <title>409 Conflict</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>409</h1>
    <p>Oops! There was a conflict with the current state of the resource.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

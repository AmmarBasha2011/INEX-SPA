<?php
/**
 * Renders the 406 Not Acceptable error page.
 *
 * This script is displayed when the server cannot generate a response that
 * matches the list of acceptable values defined in the request's proactive
 * content negotiation headers (e.g., Accept, Accept-Charset, Accept-Encoding).
 * The page informs the user of this content negotiation failure.
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
    <title>406 Not Acceptable</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>406</h1>
    <p>Not Acceptable! The requested resource cannot generate content according to the Accept headers sent in the request.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>

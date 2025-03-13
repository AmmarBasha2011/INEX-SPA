<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to INEX SPA</title>
    <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container">
        <div class="glowing-text">
            <h1>INEX SPA</h1>
        </div>
        <p class="subtitle">
            A High-Performance PHP Framework similar to NextJS/React, but lighter and faster. 
            Built with PHP, under 100KB, optimized for performance, and compatible with standard Apache servers.
        </p>
        <p class="version">Version <?php echo getEnvValue('VERSION'); ?></p>
        <p class="edit-prompt">Let's Get Started by edit web/index.php!!!</p>
        <div class="cta-button">
            <a href="https://github.com/AmmarBasha2011/INEX-SPA">Get Started</a>
        </div>
        </div>
        <div class="performance-info" id="performanceInfo">
        Loading...
        </div>
        <script src="script.js"></script>
    </body>
</html>
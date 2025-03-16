<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to INEX SPA</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body>
        <div id="contentBox" class="container">
        <div class="glowing-text">
            <h1>INEX SPA</h1>
        </div>
        <p class="subtitle">
            A High-Performance PHP Framework similar to NextJS/React, but lighter and faster. 
            Built with PHP, under 1M, optimized for performance, and compatible with standard Apache servers.
        </p>
        <p class="version">Version <?php echo getEnvValue('VERSION'); ?></p>
        <p class="edit-prompt">Let's Get Started by edit web/index.php!!!</p>
        <div class="cta-button">
            <a href="https://github.com/AmmarBasha2011/INEX-SPA">Get Started</a>
        </div>
        </div>
        
        <div id="toggleIcon">
            <i class="fa-solid fa-chevron-down"></i>
        </div>
        
        <div id="appDetailsTable" style="display: none;">
            <table border="1" style="margin: 0 auto; color: #fff; background-color: rgba(0,0,0,0.5);">
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
                <tr>
                    <td>Application Name</td>
                    <td><?php echo getEnvValue('APP_NAME'); ?></td>
                </tr>
                <tr>
                    <td>Website URL</td>
                    <td><?php echo getEnvValue('WEBSITE_URL'); ?></td>
                </tr>
                <tr>
                    <td>Version</td>
                    <td><?php echo getEnvValue('VERSION'); ?></td>
                </tr>
                <tr>
                    <td>Development Mode</td>
                    <td><?php echo getEnvValue('DEV_MODE'); ?></td>
                </tr>
                <!-- New rows for MySQL information -->
                <tr>
                    <td>DB Host</td>
                    <td><?php echo getEnvValue('DB_HOST'); ?></td>
                </tr>
                <tr>
                    <td>DB User</td>
                    <td><?php echo getEnvValue('DB_USER'); ?></td>
                </tr>
                <tr>
                    <td>DB Password</td>
                    <td><?php echo getEnvValue('DB_PASS'); ?></td>
                </tr>
                <tr>
                    <td>DB Name</td>
                    <td><?php echo getEnvValue('DB_NAME'); ?></td>
                </tr>
                <tr>
                    <td>DB Use</td>
                    <td><?php echo getEnvValue('DB_USE'); ?></td>
                </tr>
                <tr>
                    <td>Required HTTPS</td>
                    <td><?php echo getEnvValue('REQUIRED_HTTPS'); ?></td>
                </tr>
                <tr>
                    <td>Use Bootstrap</td>
                    <td><?php echo getEnvValue('USE_BOOTSTRAP'); ?></td>
                </tr>
            </table>
        </div>
        
        <div class="performance-info" id="performanceInfo">
        Loading...
        </div>
        <?php
        echo '<script src="' . getEnvValue('WEBSITE_URL') . 'script.js"></script>';
        ?>
    </body>
</html>
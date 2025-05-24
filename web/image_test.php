<?php

// This will ensure image.php (and thus the image() function) is loaded.
// It assumes functions.php is in the root and correctly includes image.php
require_once __DIR__ . '/../functions.php';

// Define APP_URL if not already defined, as some functions might rely on it
// (adjust if your framework defines this elsewhere or in a different way)
if (!defined('APP_URL')) {
    // Basic detection of server protocol and host
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    define('APP_URL', $protocol . $host);
}
// Similarly, for PUBLIC_PATH if the JS needs it (though compressor.js path is hardcoded for now)
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', APP_URL . '/public');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Helper Test</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .image-container { margin-bottom: 30px; padding: 10px; border: 1px solid #eee; }
        .image-container h3 { margin-top: 0; }
        img { border: 1px solid #ddd; max-width: 100%; height: auto; }
    </style>
</head>
<body>

    <h1>Image Helper Test Page</h1>

    <p>
        This page tests the `image()` helper function. Images should be converted to WebP
        (if supported by the browser) and resized/compressed on the client-side.
        The original images are from via.placeholder.com.
    </p>

    <div class="image-container">
        <h3>1. WebP, 300x200, Quality 0.7</h3>
        <?php echo image('https://via.placeholder.com/600x400.png?text=Original+600x400+PNG', 'Test Image 1 - WebP 300x200 Q0.7', 300, 200, 0.7, 'webp', ['class' => 'test-img']); ?>
    </div>

    <div class="image-container">
        <h3>2. WebP, Width 400 (auto height), Quality 0.9</h3>
        <?php echo image('https://via.placeholder.com/800x600.jpg?text=Original+800x600+JPG', 'Test Image 2 - WebP W400 Q0.9', 400, null, 0.9, 'webp', ['class' => 'test-img']); ?>
    </div>

    <div class="image-container">
        <h3>3. JPEG, Height 150 (auto width), Quality 0.6</h3>
        <?php echo image('https://via.placeholder.com/400x300.gif?text=Original+400x300+GIF', 'Test Image 3 - JPEG H150 Q0.6', null, 150, 0.6, 'jpeg', ['class' => 'test-img']); ?>
    </div>
    
    <div class="image-container">
        <h3>4. PNG (original format), 200x200, Quality 0.8 (PNG quality may not apply with Compressor.js)</h3>
        <?php echo image('https://via.placeholder.com/300x300.png?text=Original+300x300+PNG', 'Test Image 4 - PNG 200x200', 200, 200, 0.8, 'png', ['class' => 'test-img']); ?>
    </div>

    <div class="image-container">
        <h3>5. WebP, No dimensions (original size), Quality 0.5</h3>
        <?php echo image('https://via.placeholder.com/500x500.jpg?text=Original+500x500+JPG', 'Test Image 5 - WebP Original Size Q0.5', null, null, 0.5, 'webp', ['class' => 'test-img']); ?>
    </div>
    
    <div class="image-container">
        <h3>6. Original Image (simulating JS failure or non-WebP browser by requesting JPG directly)</h3>
        <p>This image uses the helper but requests JPG. If Compressor.js fails or is disabled, it should still show. If WebP is not supported but JPG is requested, it should process to JPG.</p>
        <?php echo image('https://via.placeholder.com/450x350.png?text=Original+450x350+PNG', 'Test Image 6 - JPG Output', 225, 175, 0.8, 'jpeg', ['class' => 'test-img']); ?>
    </div>

    <!-- 
    The image() function will attempt to load Compressor.js dynamically if not present.
    No need to manually include <script src="/public/JS/libs/compressor.min.js"></script> here
    unless you want to load it proactively or if the dynamic loading within image() has issues.
    -->

</body>
</html>
?>

<?php

// Ensure getEnvValue is available.
// Assuming it's loaded globally or autoloaded. If not, it might need a require_once here.
// For example: require_once __DIR__ . '/getEnvValue.php'; 
// However, based on file structure, it's likely loaded if other core functions are.

if (!function_exists('image')) {
    /**
     * Generates an HTML img tag.
     * If USE_IMAGE_OPTIMIZATION_BETA is true in .env, it enables client-side image optimization.
     * Otherwise, it outputs a standard img tag.
     *
     * Currently supports JPEG, PNG, and WebP via Compressor.js. AVIF is not supported
     * by the chosen client-side library.
     *
     * @param string $src Path to the original image.
     * @param string $alt Alt text for the image.
     * @param int|null $width Desired width of the image.
     * @param int|null $height Desired height of the image.
     * @param float $quality Compression quality (0.0 to 1.0) if optimization is active. Default 0.8.
     * @param string $format Target format ('webp', 'jpeg', 'png') if optimization is active. Default 'webp'.
     * @param array $attributes Additional HTML attributes for the img tag (e.g., ['class' => 'my-image']).
     * @return string HTML output for the image and accompanying JavaScript (if active).
     */
    function image($src, $alt = '', $width = null, $height = null, $quality = 0.8, $format = 'webp', $attributes = []) {
        // Construct basic image attributes string
        $imgAttributes = '';
        if (!empty($alt)) {
            $imgAttributes .= 'alt="' . htmlspecialchars($alt) . '" ';
        }
        foreach ($attributes as $key => $value) {
            $imgAttributes .= htmlspecialchars($key) . '="' . htmlspecialchars($value) . '" ';
        }

        // Check the Beta flag from .env
        // Convert to boolean: 'true', '1', 'yes', 'on' are true, others false.
        $useBetaOptimization = false;
        $envValue = getEnvValue('USE_IMAGE_OPTIMIZATION_BETA');
        if ($envValue !== null) {
            $useBetaOptimization = filter_var($envValue, FILTER_VALIDATE_BOOLEAN);
        }

        if (!$useBetaOptimization) {
            // Beta feature is off, output standard img tag
            $output = '<img src="' . htmlspecialchars($src) . '" ' . $imgAttributes;
            if ($width !== null) {
                $output .= ' width="' . htmlspecialchars(intval($width)) . '"';
            }
            if ($height !== null) {
                $output .= ' height="' . htmlspecialchars(intval($height)) . '"';
            }
            $output .= '>';
            return $output;
        }

        // --- Beta feature is ON: Proceed with optimization logic ---

        // Generate a unique ID for the image element to target with JavaScript
        $imageId = 'optimg_' . uniqid();

        // Initial img tag pointing to the original image (fallback)
        // Width/height here are for layout, JS will resize the image content
        $output = '<img id="' . $imageId . '" src="' . htmlspecialchars($src) . '" ' . $imgAttributes;
        if ($width !== null) {
            $output .= ' width="' . htmlspecialchars(intval($width)) . '"';
        }
        if ($height !== null) {
            $output .= ' height="' . htmlspecialchars(intval($height)) . '"';
        }
        $output .= '>';

        // Determine target MIME type
        $mimeType = 'image/webp'; // Default to webp
        if ($format === 'jpeg') {
            $mimeType = 'image/jpeg';
        } elseif ($format === 'png') {
            $mimeType = 'image/png';
        }
        
        // Sanitize numeric values for JS
        $jsQuality = floatval($quality);
        $jsWidth = ($width !== null) ? intval($width) : 'undefined';
        $jsHeight = ($height !== null) ? intval($height) : 'undefined';
        $escapedSrc = htmlspecialchars($src, ENT_QUOTES, 'UTF-8'); // Used multiple times in JS

        // JavaScript for client-side processing
        $output .= '
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const imageElement = document.getElementById("' . $imageId . '");
        const originalSrc = "' . $escapedSrc . '";

        if (!imageElement) {
            console.error("Image element not found: #' . $imageId . '");
            return;
        }

        function optimizeImage() {
            fetch(originalSrc)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok for image: " + originalSrc);
                    }
                    return response.blob();
                })
                .then(blob => {
                    new Compressor(blob, {
                        quality: ' . $jsQuality . ',
                        width: ' . $jsWidth . ',
                        height: ' . $jsHeight . ',
                        mimeType: "' . $mimeType . '",
                        success(result) {
                            const imageUrl = URL.createObjectURL(result);
                            imageElement.src = imageUrl;
                            // imageElement.onload = () => URL.revokeObjectURL(imageUrl); // Optional: free memory
                        },
                        error(err) {
                            console.error("Compressor.js error for ' . $escapedSrc . ':", err.message);
                        },
                    });
                })
                .catch(error => {
                    console.error("Error fetching or compressing image ' . $escapedSrc . ':", error);
                });
        }

        function checkSupportAndOptimize() {
            if ("' . $mimeType . '" === "image/webp") {
                const webPCanvas = document.createElement("canvas");
                if (webPCanvas.toDataURL("image/webp").indexOf("data:image/webp") === 0) {
                    optimizeImage();
                } else {
                    // console.log("Browser does not support WebP, serving original for ' . $escapedSrc . '.");
                }
            } else {
                optimizeImage();
            }
        }

        if (typeof Compressor === "undefined") {
            console.warn("Compressor.js is not loaded. Attempting to load for ' . $escapedSrc . '...");
            var script = document.createElement("script");
            // IMPORTANT: Adjust this path if your public JS libs folder is different
            script.src = "/public/JS/libs/compressor.min.js"; 
            script.async = true;
            script.onload = function() {
                console.log("Compressor.js loaded dynamically.");
                if (typeof Compressor !== "undefined") {
                    checkSupportAndOptimize();
                } else {
                    console.error("Failed to define Compressor after dynamic load for ' . $escapedSrc . '.");
                }
            };
            script.onerror = function() {
                console.error("Failed to load Compressor.js dynamically for ' . $escapedSrc . '.");
            };
            document.head.appendChild(script);
        } else {
            checkSupportAndOptimize();
        }
    });
</script>';

        return $output;
    }
}

?>

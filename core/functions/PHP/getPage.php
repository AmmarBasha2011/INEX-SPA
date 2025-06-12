<?php
// core/functions/PHP/getPage.php

// loadBootstrap() and loadScripts() are intentionally NOT here.
// They have been moved to index.php to be globally available.

if (!function_exists('handleRequestMethod')) {
    function handleRequestMethod(\$methods) {
        global \$Ahmed; // Assuming \$Ahmed (AhmedTemplate instance) is globally available

        // Ensure \$_GET['page'] is set, otherwise this function might not behave as expected.
        if (!isset(\$_GET['page'])) {
            // This case should ideally be handled before calling handleRequestMethod
            // or getPage, perhaps by setting \$_GET['page'] to a default.
            return false;
        }

        foreach (\$methods as \$method) {
            \$page_key = \$_GET['page'] ?? ''; // Use a default if not set to avoid warnings
            \$filePath = "web/" . \$page_key . "_request_{\$method}.ahmed.php";
            if (file_exists(\$filePath)) {
                if (\$_SERVER['REQUEST_METHOD'] !== strtoupper(\$method)) { // Ensure method comparison is case-insensitive
                    if (function_exists('loadScripts')) loadScripts(); // Call global loadScripts
                    http_response_code(405); // Method Not Allowed
                    include 'core/errors/405.php';
                    return true; // Stop further processing
                }
                if (function_exists('loadBootstrap')) loadBootstrap(); // Call global loadBootstrap
                if (function_exists('loadScripts')) loadScripts();   // Call global loadScripts
                echo \$Ahmed->render(\$filePath);
                return true; // Handled
            }
            \$filePathApi = "web/" . \$page_key . "_request_{\$method}_api.ahmed.php";
            if (file_exists(\$filePathApi)) {
                if (\$_SERVER['REQUEST_METHOD'] !== strtoupper(\$method)) {
                     // No loadScripts for API 405 for consistency, or decide if needed
                    http_response_code(405);
                    include 'core/errors/405.php'; // Or a JSON error response
                    return true;
                }
                // No loadBootstrap or loadScripts for API requests typically
                echo \$Ahmed->render(\$filePathApi);
                return true; // Handled
            }
        }
        return false; // Not handled by this function
    }
}


if (!function_exists('getPage')) {
    function getPage(\$RouteName) {
        global \$Ahmed; // Assuming \$Ahmed (AhmedTemplate instance) is globally available

        // Fallback for \$_GET['page'] if RouteName is directly used.
        // The original getPage directly modified \$_GET['page'].
        \$_GET['page'] = \$RouteName;
        \$page_key = \$RouteName; // Use a local variable for safety

        if (empty(\$page_key)) {
            if (file_exists('web/index.ahmed.php')) {
                if (function_exists('loadBootstrap')) loadBootstrap();
                if (function_exists('loadScripts')) loadScripts();
                echo \$Ahmed->render("web/index.ahmed.php");
            } elseif (file_exists('core/errors/404.php')) {
                if (function_exists('loadScripts')) loadScripts();
                http_response_code(404);
                include 'core/errors/404.php';
            }
            return;
        }

        // Special hardcoded routes from original getPage
        if (\$page_key == "fetchCsrfToken") {
            // Assumes generateCsrfToken() is available globally (it is in index.php)
            if (function_exists('generateCsrfToken')) {
                echo generateCsrfToken();
            }
            return;
        }

        if (\$page_key == 'blocked') {
            if (file_exists('core/errors/403.php')) { // Original path was __DIR__ . '/../../../core/errors/403.php'
                if (function_exists('loadScripts')) loadScripts();
                http_response_code(403);
                include 'core/errors/403.php';
            }
            return;
        }

        if (\$page_key == "JS/getWEBSITEURLValue.js") {
            // Assumes getWEBSITEURLValue() is available globally (it is in index.php)
            if (function_exists('getWEBSITEURLValue')) {
                echo getWEBSITEURLValue();
            }
            return;
        }

        if (\$page_key == "setLanguage" && getEnvValue('DETECT_LANGUAGE') == 'true') {
            if (isset(\$_POST['lang'])) {
                \$lang = \$_POST['lang'];
                setcookie('lang', \$lang, time() + (86400 * 30), "/"); // 30 days
                // Original code did not redirect or give output here.
                // Consider if redirect is needed: redirect(getEnvValue('WEBSITE_URL'));
            }
            return; // Stop further processing for setLanguage
        }

        // Attempt to load direct .ahmed.php files from web/
        if (file_exists("web/" . \$page_key . ".ahmed.php")) {
            if (function_exists('loadBootstrap')) loadBootstrap();
            if (function_exists('loadScripts')) loadScripts();
            echo \$Ahmed->render("web/" . \$page_key . ".ahmed.php");
            return;
        }

        // Attempt to serve files directly from public/
        // This is generally not recommended to be handled in PHP for actual static assets like CSS/JS/images
        // but was in the original getPage.php.
        if (file_exists("public/" . \$page_key) && is_file("public/" . \$page_key)) {
            // Determine content type based on file extension for basic asset types
            \$extension = strtolower(pathinfo(\$page_key, PATHINFO_EXTENSION));
            switch (\$extension) {
                case 'css':
                    header('Content-Type: text/css');
                    break;
                case 'js':
                    header('Content-Type: application/javascript');
                    break;
                // Add more common types if necessary (png, jpg, etc.)
            }
            readfile("public/" . \$page_key);
            return;
        }

        // Handle dynamic routes using getSlashData (e.g., page/data)
        // Assumes getSlashData() is available (restored in getSlashData.php)
        if (function_exists('getSlashData')) {
            \$RouteData = getSlashData(\$page_key);
            if (\$RouteData !== "Not Found" && is_array(\$RouteData)) {
                // Original logic: if (\$RouteData['after'] == "") { include 'core/errors/400.php'; return; }
                // This implies 'after' must exist. The restored getSlashData ensures this.

                \$_GET['data'] = \$RouteData['after']; // Make dynamic part available
                \$dynamic_page_key = \$RouteData['before'];

                if (file_exists("web/" . \$dynamic_page_key . "_dynamic.ahmed.php")) {
                    if (function_exists('loadBootstrap')) loadBootstrap();
                    if (function_exists('loadScripts')) loadScripts();
                    echo \$Ahmed->render("web/" . \$dynamic_page_key . "_dynamic.ahmed.php");
                    return;
                }
                if (file_exists("web/" . \$dynamic_page_key . "_dynamic_api.ahmed.php")) {
                    // No loadBootstrap or loadScripts for API templates
                    echo \$Ahmed->render("web/" . \$dynamic_page_key . "_dynamic_api.ahmed.php");
                    return;
                }
            }
        }

        // Handle request methods (GET, POST, PUT, DELETE, PATCH)
        // This was originally a call to handleRequestMethod().
        // The definition of handleRequestMethod is included above.
        if (handleRequestMethod(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'])) {
            return; // Handled by handleRequestMethod
        }

        // If no route matched, display 404 error
        if (file_exists('core/errors/404.php')) {
            if (function_exists('loadScripts')) loadScripts();
            http_response_code(404);
            include 'core/errors/404.php';
        } else {
            http_response_code(404);
            echo "Error 404: Page Not Found (Fallback)";
        }
        return;
    }
}
?>

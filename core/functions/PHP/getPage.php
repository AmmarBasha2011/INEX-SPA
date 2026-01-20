<?php

/**
 * Conditionally loads Bootstrap CSS and JavaScript assets from a CDN.
 *
 * This function checks the 'USE_BOOTSTRAP' environment variable. If it is set to 'true',
 * it echoes the necessary `<link>` and `<script>` tags to include Bootstrap 5,
 * a popular front-end framework, in the page.
 *
 * @return void
 */
function loadBootstrap()
{
    if (getEnvValue('USE_BOOTSTRAP') == 'true') {
        echo '<link href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">';
        echo '<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>';
    }
}

/**
 * Conditionally loads assets required for Progressive Web App (PWA) functionality.
 *
 * This function checks the 'USE_PWA' environment variable. If set to 'true', it
 * injects the PWA manifest configuration from `public/manifest_config.html` and
 * includes the main PWA JavaScript file (`JS/pwa.js`), enabling offline capabilities
 * and app-like features.
 *
 * @return void
 */
function loadPWA()
{
    if (getEnvValue('USE_PWA') == 'true') {
        $manifest_config = file_get_contents(__DIR__.'/../../../public/manifest_config.html');
        echo $manifest_config;
        echo '<script src="'.getEnvValue('WEBSITE_URL').'JS/pwa.js"></script>';
    }
}

/**
 * Loads all necessary JavaScript and CSS assets for the application's front-end.
 *
 * This function injects core scripts for routing and data submission, as well as optional
 * assets for features like cookie management, animations, and notifications, based on
 * `.env` settings. It uses static caching to avoid redundant processing.
 *
 * @return void
 */
function loadScripts()
{
    static $cachedScripts = null;
    if ($cachedScripts === null) {
        ob_start();
        echo "<script src='".getEnvValue('WEBSITE_URL')."JS/getWEBSITEURLValue.js'></script>";

        $scripts = [
            'JS/redirect.js',
            'JS/popstate.js',
            'JS/submitData.js',
            'JS/csrfToken.js',
            'JS/submitDataWR.js',
        ];

        if (getEnvValue('USE_COOKIE') == 'true') {
            $scripts[] = 'JS/classes/CookieManager.js';
        }

        if (getEnvValue('USE_APP_NAME_IN_TITLE') == 'true') {
            $scripts[] = 'JS/addAppNametoHTML.js';
        }

        if (getEnvValue('USE_ANIMATE') == 'true') {
            echo "<link rel='stylesheet' href='".getEnvValue('WEBSITE_URL')."css/motion-animations.css'>";
            $scripts[] = 'JS/motion_engine.js';
        }

        if (getEnvValue('USE_NOTIFICATION') == 'true') {
            echo "<link rel='stylesheet' href='".getEnvValue('WEBSITE_URL')."errors/notification.css'/>";
            $scripts[] = 'JS/showNotification.js';
        }

        foreach ($scripts as $script) {
            echo "<script src='".getEnvValue('WEBSITE_URL').$script."'></script>";
        }

        echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';

        $cachedScripts = ob_get_clean();
    }
    echo $cachedScripts;
}

/**
 * Handles routing for pages that are specific to a certain HTTP method.
 *
 * This function iterates through a list of provided HTTP methods (e.g., GET, POST)
 * and checks for corresponding route files in the `web/` directory. The expected
 * file naming conventions are `[page]_request_[METHOD].ahmed.php` for standard pages
 * and `[page]_request_[METHOD]_api.ahmed.php` for API endpoints.
 *
 * If a matching file is found for the current request's method, the function renders
 * that page. If a file is found but the request method does not match, it serves a
 * 405 Method Not Allowed error.
 *
 * @param array $methods An array of uppercase HTTP method names (e.g., ['GET', 'POST']) to check for.
 *
 * @return bool Returns `true` if a matching route file was found and the request was
 *              handled (either by rendering the page or by returning a 405 error).
 *              Returns `false` if no corresponding route files were found for any of
 *              the specified methods, allowing the routing process to continue.
 */
function handleRequestMethod($methods)
{
    global $Ahmed;

    foreach ($methods as $method) {
        $filePath = "web/{$_GET['page']}_request_{$method}.ahmed.php";
        if (file_exists($filePath)) {
            if ($_SERVER['REQUEST_METHOD'] !== $method) {
                loadScripts();
                include 'core/errors/405.php';

                return true;
            }
            loadBootstrap();
            loadScripts();
            echo $Ahmed->render($filePath);

            return true;
        }
        $filePath = "web/{$_GET['page']}_request_{$method}_api.ahmed.php";
        if (file_exists($filePath)) {
            if ($_SERVER['REQUEST_METHOD'] !== $method) {
                loadScripts();
                include 'core/errors/405.php';

                return true;
            }
            echo $Ahmed->render($filePath);

            return true;
        }
    }

    return false;
}

/**
 * Main routing function for the application.
 *
 * Directs requests to the appropriate page or handler based on the `RouteName`. Handles
 * homepage, internal routes, static pages, dynamic routes, API endpoints, and 404 errors.
 *
 * @param string $RouteName The name of the route to process.
 *
 * @return void
 */
/**
 * Main routing function for the application.
 *
 * Directs incoming requests to the appropriate page, asset, or handler based on the route name.
 * This function acts as the central controller, handling various types of routes:
 * - Homepage (`/`)
 * - Static pages (e.g., `/about`)
 * - Dynamic routes with parameters (e.g., `/post/123`)
 * - Method-specific routes (e.g., for POST or GET requests)
 * - Special internal routes for functionality like CSRF tokens and language switching.
 * If no matching route is found, it serves a 404 Not Found error page.
 *
 * @param string $RouteName The name of the route to process, typically derived from the URL.
 *
 * @return void
 */
function getPage($RouteName)
{
    global $Ahmed;

    $_GET['page'] = $RouteName;

    if (empty($_GET['page'])) {
        if (file_exists('web/index.ahmed.php')) {
            loadBootstrap();
            loadScripts();
            echo $Ahmed->render('web/index.ahmed.php');
        } elseif (file_exists('core/errors/404.php')) {
            loadScripts();
            include 'core/errors/404.php';
        }

        return;
    }

    if ($_GET['page'] == 'fetchCsrfToken') {
        echo generateCsrfToken();

        return;
    }

    if ($_GET['page'] == 'blocked') {
        if (file_exists(__DIR__.'/../../../core/errors/403.php')) {
            loadScripts();
            include __DIR__.'/../../../core/errors/403.php';
        }

        return;
    }

    if ($_GET['page'] == 'JS/getWEBSITEURLValue.js') {
        echo getWEBSITEURLValue();

        return;
    }

    if ($_GET['page'] == 'setLanguage' && getEnvValue('DETECT_LANGUAGE') == 'true') {
        if (isset($_POST['lang'])) {
            $lang = $_POST['lang'];
            setcookie('lang', $lang, time() + (86400 * 30), '/'); // Store for 30 days

            return;
        }
    }

    if (file_exists("web/{$_GET['page']}.ahmed.php")) {
        loadBootstrap();
        loadScripts();
        echo $Ahmed->render("web/{$_GET['page']}.ahmed.php");

        return;
    }

    if (file_exists("public/{$_GET['page']}") && is_file("public/{$_GET['page']}")) {
        include "public/{$_GET['page']}";

        return;
    }

    $RouteData = getSlashData($_GET['page']);
    if ($RouteData !== 'Not Found') {
        if ($RouteData['after'] == '') {
            loadScripts();
            include 'core/errors/400.php';

            return;
        }
        $_GET['data'] = $RouteData['after'];
        if (file_exists("web/{$RouteData['before']}_dynamic.ahmed.php")) {
            loadBootstrap();
            loadScripts();
            echo $Ahmed->render("web/{$RouteData['before']}_dynamic.ahmed.php");

            return;
        }
        if (file_exists("web/{$RouteData['before']}_dynamic_api.ahmed.php")) {
            echo $Ahmed->render("web/{$RouteData['before']}_dynamic_api.ahmed.php");

            return;
        }
    }

    if (handleRequestMethod(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'])) {
        return;
    }

    if (file_exists('core/errors/404.php')) {
        loadScripts();
        include 'core/errors/404.php';

        return;
    }
}

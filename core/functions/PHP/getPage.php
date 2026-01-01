<?php

/**
 * Conditionally loads Bootstrap CSS and JavaScript assets from a CDN.
 *
 * This function checks the 'USE_BOOTSTRAP' environment variable. If it is set to 'true',
 * it echoes the necessary `<link>` and `<script>` tags to include the Bootstrap 5
 * CSS and JavaScript bundle from the unpkg CDN. This allows for easy integration
 * of the Bootstrap framework on demand.
 *
 * @return void This function outputs HTML directly and does not return a value.
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
 * This function checks the 'USE_PWA' environment variable. If it is set to 'true',
 * it enables PWA features by injecting the necessary HTML into the document head.
 * This includes reading and outputting the content of `public/manifest_config.html`
 * (which typically contains the `<link rel="manifest">` tag and theme color meta tags)
 * and adding a `<script>` tag for the PWA service worker registration script (`JS/pwa.js`).
 *
 * @return void This function outputs HTML directly and does not return a value.
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
 * This function is responsible for injecting all core and conditionally-loaded
 * JavaScript and CSS files into the page. It ensures that essential scripts for
 * routing, data submission, and CSRF are always included. Additionally, it checks
 * environment variables (from `.env`) to conditionally include assets for features
 * like cookie management, PWA, animations, and notifications.
 *
 * To optimize performance, it uses a static variable (`$cachedScripts`) to cache
 * the generated script and link tags, ensuring the file system and environment
 * variables are only read once per request.
 *
 * @return void This function outputs the HTML tags for the assets directly and does
 *              not return a value.
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
 * Handles routing for requests that are specific to a certain HTTP method.
 *
 * This function is a core part of the routing mechanism. It checks for template files
 * that follow the naming convention `[page]_request_[METHOD].ahmed.php` or
 * `[page]_request_[METHOD]_api.ahmed.php`. If a matching file is found for the current
 * request's page, it verifies that the HTTP method also matches. If the method is incorrect,
 * it renders a 405 Method Not Allowed error page. If both match, it renders the
 * corresponding template.
 *
 * @global AhmedTemplate $Ahmed The global instance of the template engine.
 *
 * @param array $methods An array of uppercase HTTP method names (e.g., ['GET', 'POST'])
 *                       to check for.
 *
 * @return bool Returns `true` if a route was successfully found and handled (either by
 *              rendering the page or an error). Returns `false` if no matching
 *              request-method-specific route file was found, allowing the routing
 *              process to continue.
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
 * Main routing and page rendering function for the application.
 *
 * This function acts as the central router. It takes a route name and determines which
 * content to display. It follows a specific order of checks:
 * 1.  Handles the homepage if the route name is empty.
 * 2.  Checks for special internal routes (e.g., 'fetchCsrfToken', 'blocked', 'setLanguage').
 * 3.  Looks for a direct match for a template file in the `/web` directory.
 * 4.  Checks if the route corresponds to a static file in the `/public` directory.
 * 5.  Parses the route for a dynamic pattern (e.g., 'resource/id') and looks for a
 *     corresponding `_dynamic.ahmed.php` template.
 * 6.  Delegates to `handleRequestMethod()` to check for method-specific routes (GET, POST, etc.).
 * 7.  If no match is found after all checks, it renders a 404 Not Found error page.
 *
 * @global AhmedTemplate $Ahmed The global instance of the template engine.
 *
 * @param string $RouteName The name of the route to process, typically from the URL.
 *
 * @return void This function outputs directly and does not return a value.
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

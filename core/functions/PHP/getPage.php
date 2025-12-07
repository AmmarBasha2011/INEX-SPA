<?php

/**
 * Loads the Bootstrap CSS and JS files if enabled in the .env file.
 */
function loadBootstrap()
{
    if (getEnvValue('USE_BOOTSTRAP') == 'true') {
        echo '<link href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">';
        echo '<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>';
    }
}

/**
 * Loads the PWA manifest and service worker if enabled in the .env file.
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
 * Loads the necessary JavaScript files.
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

        if (getEnvValue('DEV_MODE') == 'true') {
            echo "<link rel='stylesheet' href='".getEnvValue('WEBSITE_URL')."css/devtools.css'>";
            $scripts[] = 'JS/devtools.js';
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
 * Handles the request method for a given page.
 *
 * @param array $methods An array of allowed HTTP methods.
 *
 * @return bool True if the request method was handled, false otherwise.
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
 * Loads the requested page.
 *
 * @param string $RouteName The name of the route to load.
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
            setcookie('lang', $lang, time() + (86400 * 30), '/');

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
    }
}

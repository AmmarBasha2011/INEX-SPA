<?php

function loadBootstrap()
{
    if (getEnvValue('USE_BOOTSTRAP') == 'true') {
        return '<link href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">'.
               '<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>';
    }

    return '';
}

function loadPWA()
{
    if (getEnvValue('USE_PWA') == 'true') {
        $manifest_config = file_get_contents(__DIR__.'/../../../public/manifest_config.html');

        return $manifest_config.
               '<script src="'.getEnvValue('WEBSITE_URL').'JS/pwa.js"></script>';
    }

    return '';
}

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
            $scripts[] = 'JS/classes/CookieManager.js'; // Correct way to append an element to an array
        }

        if (getEnvValue('USE_APP_NAME_IN_TITLE') == 'true') {
            $scripts[] = 'JS/addAppNametoHTML.js';
        }

        // Add this new block for motion engine assets
        if (getEnvValue('USE_ANIMATE') == 'true') {
            echo "<link rel='stylesheet' href='".getEnvValue('WEBSITE_URL')."css/motion-animations.css'>";
            $scripts[] = 'JS/motion_engine.js'; // Add to the array of scripts
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

    return $cachedScripts;
}

function handleRequestMethod($methods)
{
    global $Ahmed;

    foreach ($methods as $method) {
        $filePath = "web/{$_GET['page']}_request_{$method}.ahmed.php";
        if (file_exists($filePath)) {
            if ($_SERVER['REQUEST_METHOD'] !== $method) {
                ob_start();
                loadScripts();
                include 'core/errors/405.php';

                return ob_get_clean();
            }
            ob_start();
            loadBootstrap();
            loadScripts();
            echo $Ahmed->render($filePath);

            return ob_get_clean();
        }
        $filePath = "web/{$_GET['page']}_request_{$method}_api.ahmed.php";
        if (file_exists($filePath)) {
            if ($_SERVER['REQUEST_METHOD'] !== $method) {
                ob_start();
                loadScripts();
                include 'core/errors/405.php';

                return ob_get_clean();
            }

            return $Ahmed->render($filePath);
        }
    }

    return false;
}

function getPage($RouteName)
{
    global $Ahmed;

    $_GET['page'] = $RouteName;
    ob_start();

    if (empty($_GET['page'])) {
        if (file_exists('web/index.ahmed.php')) {
            echo loadBootstrap();
            echo loadScripts();
            echo $Ahmed->render('web/index.ahmed.php');
        } elseif (file_exists('core/errors/404.php')) {
            echo loadScripts();
            include 'core/errors/404.php';
        }
    } elseif ($_GET['page'] == 'fetchCsrfToken') {
        echo generateCsrfToken();
    } elseif ($_GET['page'] == 'blocked') {
        if (file_exists(__DIR__.'/../../../core/errors/403.php')) {
            echo loadScripts();
            include __DIR__.'/../../../core/errors/403.php';
        }
    } elseif ($_GET['page'] == 'JS/getWEBSITEURLValue.js') {
        echo getWEBSITEURLValue();
    } elseif ($_GET['page'] == 'setLanguage' && getEnvValue('DETECT_LANGUAGE') == 'true') {
        if (isset($_POST['lang'])) {
            $lang = $_POST['lang'];
            setcookie('lang', $lang, time() + (86400 * 30), '/'); // Store for 30 days
        }
    } elseif (file_exists("web/{$_GET['page']}.ahmed.php")) {
        echo loadBootstrap();
        echo loadScripts();
        echo $Ahmed->render("web/{$_GET['page']}.ahmed.php");
    } elseif (file_exists("public/{$_GET['page']}") && is_file("public/{$_GET['page']}")) {
        include "public/{$_GET['page']}";
    } else {
        $RouteData = getSlashData($_GET['page']);
        if ($RouteData !== 'Not Found') {
            if ($RouteData['after'] == '') {
                echo loadScripts();
                include 'core/errors/400.php';
            } else {
                $_GET['data'] = $RouteData['after'];
                if (file_exists("web/{$RouteData['before']}_dynamic.ahmed.php")) {
                    echo loadBootstrap();
                    echo loadScripts();
                    echo $Ahmed->render("web/{$RouteData['before']}_dynamic.ahmed.php");
                } elseif (file_exists("web/{$RouteData['before']}_dynamic_api.ahmed.php")) {
                    echo $Ahmed->render("web/{$RouteData['before']}_dynamic_api.ahmed.php");
                }
            }
        } else {
            $requestMethodResult = handleRequestMethod(['GET', 'POST', 'PUT', 'DELETE', 'PATCH']);
            if ($requestMethodResult === false) {
                if (file_exists('core/errors/404.php')) {
                    echo loadScripts();
                    include 'core/errors/404.php';
                }
            } else {
                echo $requestMethodResult;
            }
        }
    }

    return ob_get_clean();
}

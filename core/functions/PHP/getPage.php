<?php

function loadBootstrap() {
    if (getEnvValue('USE_BOOTSTRAP') == 'true') {
        echo '<link href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">';
        echo '<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>';
    }
}

function loadScripts() {
    static $cachedScripts = null;
    if ($cachedScripts === null) {
        ob_start();
        echo '<script>' . getWEBSITEURLValue() . '</script>';
        
        $scripts = [
            'core/functions/JS/redirect.js',
            'core/functions/JS/popstate.js',
            'core/functions/JS/submitData.js',
            'core/functions/JS/csrfToken.js',
            'core/functions/JS/submitDataWR.js',
        ];
        
        if (getEnvValue('USE_COOKIE') == 'true') {
            $scripts[] = 'core/functions/JS/classes/CookieManager.js'; // Correct way to append an element to an array
        }        
        
        foreach ($scripts as $script) {
            if (file_exists($script)) {
                echo '<script>' . file_get_contents($script) . '</script>';
            }
        }
        
        echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
        
        if (getEnvValue('USE_APP_NAME_IN_TITLE') == 'true' && file_exists('functions/JS/addAppNametoHTML.js')) {
            echo '<script>' . file_get_contents('functions/JS/addAppNametoHTML.js') . '</script>';
        }
        
        $cachedScripts = ob_get_clean();
    }
    echo $cachedScripts;
}

function handleRequestMethod($methods) {
    foreach ($methods as $method) {
        $filePath = "web/{$_GET['page']}_request_{$method}.php";
        if (file_exists($filePath)) {
            if ($_SERVER['REQUEST_METHOD'] !== $method) {
                loadScripts();
                include 'core/errors/405.php';
                return true;
            }
            loadBootstrap();
            loadScripts();
            include $filePath;
            return true;
        }
        $filePath = "web/{$_GET['page']}_request_{$method}_api.php";
        if (file_exists($filePath)) {
            if ($_SERVER['REQUEST_METHOD'] !== $method) {
                loadScripts();
                include 'core/errors/405.php';
                return true;
            }
            include $filePath;
            return true;
        }
    }
    return false;
}

function getPage($RouteName) {
    $_GET['page'] = $RouteName;

    if (empty($_GET['page'])) {
        if (file_exists('web/index.php')) {
            loadBootstrap();
            loadScripts();
            include 'web/index.php';
        } elseif (file_exists('core/errors/404.php')) {
            loadScripts();
            include 'core/errors/404.php';
        }
        return;
    }
    
    if ($_GET['page'] == "fetchCsrfToken") {
        echo generateCsrfToken();
        return;
    }

    if ($_GET['page'] == "setLanguage" && getEnvValue('DETECT_LANGUAGE') == 'true') {
        if (isset($_POST['lang'])) {
            $lang = $_POST['lang'];
            setcookie('lang', $lang, time() + (86400 * 30), "/"); // Store for 30 days
            return;
        }
    }

    if (file_exists("web/{$_GET['page']}.php")) {
        loadBootstrap();
        loadScripts();
        include "web/{$_GET['page']}.php";
        return;
    }

    if (file_exists("public/{$_GET['page']}") && is_file("public/{$_GET['page']}")) {
        include "public/{$_GET['page']}";
        return;
    }

    $RouteData = getSlashData($_GET['page']);
    if ($RouteData !== "Not Found") {
        if ($RouteData['after'] == "") {
            loadScripts();
            include 'core/errors/400.php';
            return;
        }
        $_GET['data'] = $RouteData['after'];
        if (file_exists("web/{$RouteData['before']}_dynamic.php")) {
            loadBootstrap();
            loadScripts();
            include "web/{$RouteData['before']}_dynamic.php";
            return;
        }
        if (file_exists("web/{$RouteData['before']}_dynamic_api.php")) {
            include "web/{$RouteData['before']}_dynamic_api.php";
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
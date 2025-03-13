<?php
function getPage($RouteName) {
    $_GET['page'] = $RouteName;

    if (!$_GET['page'] || empty($_GET['page'])) {
        if (file_exists('web/index.php')) {
            include 'web/index.php';
        } else {
            if (file_exists('errors/404.php')) {
                include 'errors/404.php';
            } else {
                return;
            }
        }
    } else {
        if (file_exists('public/' . $_GET['page'])) {
            include 'public/' . $_GET['page'];
        } else {
            if (file_exists('web/' . $_GET['page'] . '.php')) {
                include 'web/' . $_GET['page'] . '.php';
            } else {
                if (file_exists('errors/404.php')) {
                    include 'errors/404.php';
                } else {
                    return;
                }
            }
        }
    }
}
?>
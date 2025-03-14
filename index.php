<?php
require_once 'functions/PHP/redirect.php';
require_once 'functions/PHP/getEnvValue.php';
if (getEnvValue('DEV_MODE') == 'true') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
require_once 'functions/PHP/getWEBSITEURLValue.php';
require_once 'functions/PHP/getSlashData.php';
if (getEnvValue('DB_USE') == 'true') {
    require_once 'functions/PHP/classes/Database.php';
    function executeStatement($sql, $params = [], $is_return = true) {
        $DB = new Database();
        return $DB->query($sql, $params, $is_return);
    }
}
require_once 'functions/PHP/getPage.php';
getPage(isset($_GET['page']) ? $_GET['page'] : '');
?>
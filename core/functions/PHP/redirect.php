<?php
require_once 'getPage.php';

if (isset($_GET['route'])) {
    getPage($_GET['route']);
}
?>

<script><?php echo getWEBSITEURLValue(); ?></script>
<?php
require_once 'functions/PHP/getPage.php';
require_once 'functions/PHP/redirect.php';
require_once 'functions/PHP/getEnvValue.php';
require_once 'functions/PHP/getWEBSITEURLValue.php';
getPage($_GET['page']);
?>
<script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
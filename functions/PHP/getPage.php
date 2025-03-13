<?php
function getPage($RouteName) {
    $_GET['page'] = $RouteName;

    if (!$_GET['page'] || empty($_GET['page'])) {
        if (file_exists('web/index.php')) {
            ?>
            <script><?php echo getWEBSITEURLValue(); ?></script>
            <?php
            include 'web/index.php';
            ?>
            <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
            <?php
        } else {
            if (file_exists('errors/404.php')) {
                ?>
                <script><?php echo getWEBSITEURLValue(); ?></script>
                <?php
                include 'errors/404.php';
                ?>
                <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
                <?php
            } else {
                return;
            }
        }
    } else {
        if (file_exists('public/' . $_GET['page'])) {
            include 'public/' . $_GET['page'];
        } else {
            if (file_exists('web/' . $_GET['page'] . '.php')) {
                ?>
                <script><?php echo getWEBSITEURLValue(); ?></script>
                <?php
                include 'web/' . $_GET['page'] . '.php';
                ?>
                <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
                <?php
            } else {
                if (file_exists('errors/404.php')) {
                    ?>
                    <script><?php echo getWEBSITEURLValue(); ?></script>
                    <?php
                    include 'errors/404.php';
                    ?>
                    <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
                    <?php
                } else {
                    return;
                }
            }
        }
    }
}
?>
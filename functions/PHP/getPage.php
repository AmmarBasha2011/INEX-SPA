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
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script><?php echo require_once 'functions/JS/submitData.js' ;?></script>
            <?php
        } else {
            if (file_exists('errors/404.php')) {
                ?>
                <script><?php echo getWEBSITEURLValue(); ?></script>
                <?php
                include 'errors/404.php';
                ?>
                <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script><?php echo require_once 'functions/JS/submitData.js' ;?></script>
                <?php
            } else {
                return;
            }
        }
    } else {
        if (file_exists('public/' . $_GET['page'])) {
            include 'public/' . $_GET['page'];
        } else {
            $RouteData = getSlashData($_GET['page']);
            if ($RouteData != "Not Found" && file_exists('web/' . $RouteData['before'] . '_dynamic.php')) {
                if ($RouteData['after'] == "") {
                    ?>
                    <script><?php echo getWEBSITEURLValue(); ?></script>
                    <?php
                    include 'errors/400.php';
                    ?>
                    <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script><?php echo require_once 'functions/JS/submitData.js' ;?></script>
                    <?php
                    return;
                }
                ?>
                <script><?php echo getWEBSITEURLValue(); ?></script>
                <?php
                $_GET['data'] = $RouteData['after'];
                include 'web/' . $RouteData['before'] . '_dynamic.php';
                ?>
                <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script><?php echo require_once 'functions/JS/submitData.js' ;?></script>
                <?php
                return;
            }
            $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];

            foreach ($methods as $method) {
                $filePath = 'web/' . $_GET['page'] . '_request_' . $method . '.php';

                if (file_exists($filePath)) {
                    if ($_SERVER['REQUEST_METHOD'] !== $method) {
                        ?>
                        <script><?php echo getWEBSITEURLValue(); ?></script>
                        <?php
                        include 'errors/405.php';
                        ?>
                        <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script><?php echo require_once 'functions/JS/submitData.js' ;?></script>
                        <?php
                        return;
                    }
                    ?>
                    <script><?php echo getWEBSITEURLValue(); ?></script>
                    <?php
                    include $filePath;
                    ?>
                    <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script><?php echo require_once 'functions/JS/submitData.js' ;?></script>
                    <?php
                    return;
                }
            }
            if (file_exists('web/' . $_GET['page'] . '.php')) {
                ?>
                <script><?php echo getWEBSITEURLValue(); ?></script>
                <?php
                include 'web/' . $_GET['page'] . '.php';
                ?>
                <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script><?php echo require_once 'functions/JS/submitData.js' ;?></script>
                <?php
            } else {
                if (file_exists('errors/404.php')) {
                    ?>
                    <script><?php echo getWEBSITEURLValue(); ?></script>
                    <?php
                    include 'errors/404.php';
                    ?>
                    <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script><?php echo require_once 'functions/JS/submitData.js' ;?></script>
                    <?php
                } else {
                    return;
                }
            }
        }
    }
}
?>
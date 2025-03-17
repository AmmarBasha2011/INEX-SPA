<?php
function getPage($RouteName) {
    $_GET['page'] = $RouteName;

    if (!$_GET['page'] || empty($_GET['page'])) {
        if (file_exists('web/index.php')) {
            if (getEnvValue('USE_BOOTSTRAP') == 'true') {
                echo '<link href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">';
            }
            ?>
            <script><?php echo getWEBSITEURLValue(); ?></script>
            <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
            <script><?php echo require_once 'functions/JS/popstate.js'; ?></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script><?php echo require_once 'functions/JS/submitData.js' ;?></script>
            <?php
            include 'web/index.php';
            if (getEnvValue('USE_BOOTSTRAP') == 'true') {
                echo '<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>';
            }
            if (getEnvValue("USE_APP_NAME_IN_TITLE") == "true") {
                ?>
                <script><?php echo require_once 'functions/JS/addAppNametoHTML.js'; ?></script>
                <?php
            }
            ?>
            <script><?php echo require_once 'functions/JS/csrfToken.js'; ?></script>
            <?php
        } else {
            if (file_exists('errors/404.php')) {
                ?>
                <script><?php echo getWEBSITEURLValue(); ?></script>
                <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
                <?php
                include 'errors/404.php';
            } else {
                return;
            }
        }
    } else {
        if ($_GET['page'] == "fetchCsrfToken") {
            echo generateCsrfToken();
            return;
        }
        if (file_exists('public/' . $_GET['page']) && is_file('public/' . $_GET['page'])) {
            include 'public/' . $_GET['page'];
        } else {
            $RouteData = getSlashData($_GET['page']);
            if ($RouteData != "Not Found" && file_exists('web/' . $RouteData['before'] . '_dynamic.php')) {
                if ($RouteData['after'] == "") {
                    ?>
                    <script><?php echo getWEBSITEURLValue(); ?></script>
                    <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
                    <?php
                    include 'errors/400.php';
                    return;
                }
                if (getEnvValue('USE_BOOTSTRAP') == 'true') {
                    echo '<link href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">';
                }
                ?>
                <script><?php echo getWEBSITEURLValue(); ?></script>
                <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
                <script><?php echo require_once 'functions/JS/popstate.js'; ?></script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script><?php echo require_once 'functions/JS/submitData.js' ;?></script>
                <?php
                $_GET['data'] = $RouteData['after'];
                include 'web/' . $RouteData['before'] . '_dynamic.php';
                if (getEnvValue('USE_BOOTSTRAP') == 'true') {
                    echo '<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>';
                }
                if (getEnvValue("USE_APP_NAME_IN_TITLE") == "true") {
                    ?>
                    <script><?php echo require_once 'functions/JS/addAppNametoHTML.js'; ?></script>
                    <?php
                }
                ?>
                <script><?php echo require_once 'functions/JS/csrfToken.js'; ?></script>
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
                        <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
                        <?php
                        include 'errors/405.php';
                        return;
                    }
                    if (getEnvValue('USE_BOOTSTRAP') == 'true') {
                        echo '<link href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">';
                    }
                    ?>
                    <script><?php echo getWEBSITEURLValue(); ?></script>
                    <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
                    <script><?php echo require_once 'functions/JS/popstate.js'; ?></script>
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script><?php echo require_once 'functions/JS/submitData.js' ;?></script>
                    <?php
                    include $filePath;
                    if (getEnvValue('USE_BOOTSTRAP') == 'true') {
                        echo '<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>';
                    }
                    if (getEnvValue("USE_APP_NAME_IN_TITLE") == "true") {
                        ?>
                        <script><?php echo require_once 'functions/JS/addAppNametoHTML.js'; ?></script>
                        <?php
                    }
                    ?>
                    <script><?php echo require_once 'functions/JS/csrfToken.js'; ?></script>
                    <?php
                    return;
                }
            }
            if (file_exists('web/' . $_GET['page'] . '.php')) {
                if (getEnvValue('USE_BOOTSTRAP') == 'true') {
                    echo '<link href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">';
                }
                ?>
                <script><?php echo getWEBSITEURLValue(); ?></script>
                <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
                <script><?php echo require_once 'functions/JS/popstate.js'; ?></script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script><?php echo require_once 'functions/JS/submitData.js' ;?></script>
                <?php
                include 'web/' . $_GET['page'] . '.php';
                if (getEnvValue('USE_BOOTSTRAP') == 'true') {
                    echo '<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>';
                }
                if (getEnvValue("USE_APP_NAME_IN_TITLE") == "true") {
                    ?>
                    <script><?php echo require_once 'functions/JS/addAppNametoHTML.js'; ?></script>
                    <?php
                }
                ?>
                <script><?php echo require_once 'functions/JS/csrfToken.js'; ?></script>
                <?php
            } else {
                if (file_exists('errors/404.php')) {
                    ?>
                    <script><?php echo getWEBSITEURLValue(); ?></script>
                    <script><?php echo require_once 'functions/JS/redirect.js'; ?></script>
                    <?php
                    include 'errors/404.php';
                    ?>
                    <?php
                } else {
                    return;
                }
            }
        }
    }
}
?>
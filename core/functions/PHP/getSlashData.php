<?php
// core/functions/PHP/getSlashData.php
if (!function_exists('getSlashData')) {
    function getSlashData(\$url) {
        \$parts = explode('/', \$url, 2);
        if (count(\$parts) === 2 && !empty(\$parts[0]) && !empty(\$parts[1])) { // Ensure both parts are non-empty
            return [
                'before' => \$parts[0],
                'after'  => \$parts[1]
            ];
        }
        // If the URL doesn't contain a slash, or one part is empty, it's not a valid slash data route for this function.
        return "Not Found";
    }
}
?>

<?php
function getSlashData($text) {
    $parts = explode("/", $text);
    if (count($parts) == 2) {
        $before = $parts[0];
        $after = $parts[1];
    
        return array("before" => $before, "after" => $after);
    } else {
        return "Not Found";
    }
}
?>

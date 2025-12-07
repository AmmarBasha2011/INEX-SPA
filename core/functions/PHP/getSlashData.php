<?php

/**
 * Splits a string by a slash and returns an array with the parts before and after the slash.
 *
 * @param string $text The string to split.
 *
 * @return array|string An array with the 'before' and 'after' parts, or 'Not Found' if the string does not contain a slash.
 */
function getSlashData($text)
{
    $parts = explode('/', $text);
    if (count($parts) == 2) {
        $before = $parts[0];
        $after = $parts[1];

        return ['before' => $before, 'after' => $after];
    } else {
        return 'Not Found';
    }
}

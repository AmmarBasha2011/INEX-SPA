<?php

/**
 * Splits a string by a slash and returns the two parts.
 *
 * This function is used for parsing simple routes. It splits the input string
 * by the '/' character. If the string contains exactly one slash, it returns
 * an associative array with the parts before and after the slash.
 *
 * @param string $text The input string to parse.
 * @return array|string An associative array `['before' => string, 'after' => string]`
 *                      if the split is successful, or the string 'Not Found' otherwise.
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

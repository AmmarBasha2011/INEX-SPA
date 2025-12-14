<?php

/**
 * Parses a string containing a single slash to extract the parts before and after it.
 *
 * This function is primarily used for simple dynamic routing, where a URL segment might
 * be in the format `resource/id`. It splits the input string by the `/` character.
 * If the string contains exactly one slash, it returns an associative array with the
 * parts labeled 'before' and 'after'.
 *
 * @param string $text The input string to parse, typically a URL segment.
 *
 * @return array|string Returns an associative array `['before' => string, 'after' => string]`
 *                      if the string is successfully split. Otherwise, it returns the
 *                      string 'Not Found'.
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

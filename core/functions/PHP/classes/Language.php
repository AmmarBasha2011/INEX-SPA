<?php

/**
 * Handles language translations for internationalization (i18n).
 */
class Language
{
    private static $lang = 'en';
    private static $translations = [];

    public static function setLanguage($lang)
    {
        $langFile = __DIR__."/../../../../lang/$lang.json";
        if (file_exists($langFile)) {
            self::$lang = $lang;
            self::$translations = json_decode(file_get_contents($langFile), true) ?: [];
        }
    }

    public static function get($key, $default = null, $placeholders = [])
    {
        // For backward compatibility, if the second parameter is an array, assume it's $placeholders.
        if (is_array($default)) {
            $placeholders = $default;
            $default = null;
        }

        $text = self::$translations[$key] ?? ($default !== null ? $default : $key);

        foreach ($placeholders as $placeholder => $value) {
            $text = str_replace('{'.$placeholder.'}', (string) $value, $text);
        }

        return $text;
    }
}

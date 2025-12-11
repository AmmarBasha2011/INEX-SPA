<?php

/**
 * A class for handling language translations.
 *
 * This class manages loading language files and retrieving translated strings.
 * It supports placeholders in translation strings for dynamic content.
 */
class Language
{
    /**
     * The currently active language code (e.g., 'en').
     *
     * @var string
     */
    private static $lang = 'en';

    /**
     * An associative array of translations for the active language.
     *
     * @var array
     */
    private static $translations = [];

    /**
     * Sets the active language and loads the corresponding translation file.
     *
     * @param string $lang The language code (e.g., 'en', 'fr').
     *
     * @return void
     */
    public static function setLanguage($lang)
    {
        $langFile = __DIR__."/../../../../lang/$lang.json";
        if (file_exists($langFile)) {
            self::$lang = $lang;
            self::$translations = json_decode(file_get_contents($langFile), true);
        }
    }

    /**
     * Gets a translated string for the given key.
     *
     * If the key is not found in the translations, the key itself is returned.
     * Supports replacing placeholders in the format `{placeholder}`.
     *
     * @param string $key          The translation key.
     * @param array  $placeholders An associative array of placeholders and their values.
     *
     * @return string The translated string.
     */
    public static function get($key, $placeholders = [])
    {
        $text = self::$translations[$key] ?? $key;
        foreach ($placeholders as $placeholder => $value) {
            $text = str_replace('{'.$placeholder.'}', $value, $text);
        }

        return $text;
    }
}

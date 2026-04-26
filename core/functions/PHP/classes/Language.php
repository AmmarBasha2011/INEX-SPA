<?php

/**
 * Handles language translations for internationalization (i18n).
 *
 * This static class manages loading language files from the `/lang` directory
 * and retrieving translated strings. It allows for dynamically switching the
 * active language and supports placeholder replacement in translation strings.
 */
class Language
{
    /**
     * Stores the currently active language code (e.g., 'en', 'fr').
     *
     * @var string
     */
    private static $lang = 'en';

    /**
     * Holds the translations loaded from the current language's JSON file.
     * The keys are the translation keys, and the values are the translated strings.
     *
     * @var array
     */
    private static $translations = [];

    /**
     * Sets the active language for the application.
     *
     * This method attempts to load a JSON translation file from the `/lang`
     * directory that corresponds to the provided language code. If the file
     * is found and successfully parsed, it updates the application's active
     * language and translation set.
     *
     * @param string $lang The two-letter language code (e.g., 'en', 'de', 'fr').
     *
     * @return void
     */
    public static function setLanguage($lang)
    {
        $langFile = __DIR__."/../../../../lang/$lang.json";
        if (file_exists($langFile)) {
            self::$lang = $lang;
            self::$translations = json_decode(file_get_contents($langFile), true);
            if (!is_array(self::$translations)) {
                self::$translations = [];
            }
        }
    }

    /**
     * Retrieves a translated string by its key and replaces any placeholders.
     *
     * Looks up the translation for the given key in the currently loaded language
     * set. If the key is not found, the key itself is returned as a fallback.
     * It also supports dynamic value injection by replacing placeholders in the
     * format `{placeholder_name}` with values from the `$placeholders` array.
     *
     * @param string $key          The unique key for the translation string.
     * @param mixed  $default      Default value if key not found (fallback to key).
     * @param array  $placeholders An associative array where keys are placeholder names.
     *
     * @return string The translated and formatted string, or the key if not found.
     */
    public static function get($key, $default = null, $placeholders = [])
    {
        // Backward compatibility: if second arg is array, it's placeholders
        if (is_array($default)) {
            $placeholders = $default;
            $default = null;
        }

        $text = self::$translations[$key] ?? ($default ?? $key);
        if (is_array($placeholders)) {
            foreach ($placeholders as $placeholder => $value) {
                $text = str_replace('{'.$placeholder.'}', $value, $text);
            }
        }

        return $text;
    }
}

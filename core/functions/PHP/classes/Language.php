<?php

/**
 * Handles language translations for internationalization (i18n).
 *
 * This static class manages the loading of language files from the `/lang` directory
 * and the retrieval of translated strings. It allows for the active language to be
 * switched dynamically and supports the injection of placeholder values into
 * translation strings for dynamic content.
 */
class Language
{
    /**
     * Stores the currently active language code (e.g., 'en', 'fr').
     * Defaults to 'en'.
     *
     * @var string
     */
    private static $lang = 'en';

    /**
     * Holds the translations loaded from the current language's JSON file.
     * This is an associative array where keys are the translation keys and values
     * are the corresponding translated strings.
     *
     * @var array
     */
    private static $translations = [];

    /**
     * Sets the active language for the application and loads its translation file.
     *
     * This method attempts to load a JSON translation file from the `/lang`
     * directory that corresponds to the provided language code (e.g., `lang/en.json`).
     * If the file is found and successfully parsed, it updates the application's active
     * language and translation set. If the file is not found, the previously loaded
     * language remains active.
     *
     * @param string $lang The two-letter language code (e.g., 'en', 'de', 'fr')
     *                     for the desired language.
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
     * Retrieves a translated string by its key and replaces any placeholders.
     *
     * This method looks up the translation for the given key in the currently loaded language
     * set. If the key is not found, the key itself is returned as a fallback to aid in
     * development. It also supports dynamic value injection by replacing placeholders in the
     * format `{placeholder_name}` with values from the `$placeholders` array.
     *
     * Example: `Language::get('welcome_message', ['name' => 'John'])`
     *
     * @param string $key          The unique key for the translation string (e.g., 'welcome_message').
     * @param array  $placeholders (Optional) An associative array where keys are placeholder names
     *                             (without curly braces) and values are the strings to
     *                             be injected.
     *
     * @return string The translated and formatted string, or the key itself if the translation is not found.
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

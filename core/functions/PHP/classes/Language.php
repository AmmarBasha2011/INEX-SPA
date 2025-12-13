<?php
/**
 * Language and Translation Management
 *
 * This file provides the Language class, a static utility for managing
 * multilingual support in the application.
 */

/**
 * A static class for handling language translations.
 *
 * This class manages loading language files (in JSON format) and retrieving
 * translated strings by a given key. It supports dynamic placeholders in
 * translation strings for variable content. All methods are static.
 *
 * @package INEX\Localization
 */
class Language
{
    /**
     * The code for the currently active language (e.g., 'en', 'ar').
     *
     * @var string
     */
    private static $lang = 'en';

    /**
     * An associative array holding the translations for the active language.
     * The keys are the translation keys and the values are the translated strings.
     *
     * @var array
     */
    private static $translations = [];

    /**
     * Sets the active language and loads the corresponding translation file.
     *
     * If a language file for the given code exists in the `lang/` directory,
     * it is loaded and parsed. If the file does not exist, the currently
     * loaded language remains unchanged.
     *
     * @param string $lang The language code to activate (e.g., 'en', 'fr').
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
     * Gets a translated string for a given key, with optional placeholders.
     *
     * Retrieves the translation for the specified key from the currently loaded
     * language file. If the key is not found, the key itself is returned as a fallback.
     * Placeholders in the format `{placeholder}` will be replaced with their
     * corresponding values from the `$placeholders` array.
     *
     * @param string $key          The translation key (e.g., 'welcome_message').
     * @param array  $placeholders An associative array of placeholders and their values
     *                             (e.g., ['username' => 'John']).
     *
     * @return string The translated and formatted string.
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

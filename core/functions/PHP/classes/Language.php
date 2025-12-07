<?php

/**
 * A class for handling language translations.
 */
class Language
{
    /**
     * The current language.
     *
     * @var string
     */
    private static $lang = 'en';

    /**
     * The translations for the current language.
     *
     * @var array
     */
    private static $translations = [];

    /**
     * Sets the current language.
     *
     * @param string $lang The language to set.
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
     * Gets a translation for a given key.
     *
     * @param string $key          The translation key.
     * @param array  $placeholders An array of placeholders to replace in the translation.
     *
     * @return string The translated text.
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

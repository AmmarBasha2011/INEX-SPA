<?php

class Language {
    private static $lang = 'en';
    private static $translations = [];

    public static function setLanguage($lang) {
        $langFile = __DIR__ . "/../../../../lang/$lang.json";
        if (file_exists($langFile)) {
            self::$lang = $lang;
            self::$translations = json_decode(file_get_contents($langFile), true);
        }
    }

    public static function get($key, $placeholders = []) {
        $text = self::$translations[$key] ?? $key;
        foreach ($placeholders as $placeholder => $value) {
            $text = str_replace("{" . $placeholder . "}", $value, $text);
        }
        return $text;
    }
}

// Detect language from cookie or default to English
// $selectedLang = $_COOKIE['lang'] ?? 'en';
// Language::setLanguage($selectedLang);

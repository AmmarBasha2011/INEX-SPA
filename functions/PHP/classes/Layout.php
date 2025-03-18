<?php

class Layout {
    private static $sections = [];
    private static $currentSection = null;
    
    public static function start($section) {
        if (self::$currentSection !== null) {
            die("❌ Error: Nested sections are not allowed!");
        }
        self::$currentSection = $section;
        ob_start();
    }

    public static function end() {
        if (self::$currentSection === null) {
            die("❌ Error: No section has been started!");
        }
        self::$sections[self::$currentSection] = ob_get_clean();
        self::$currentSection = null;
    }

    public static function render($layoutName, $contentFile, $requestType = 'GET', $data = []) {
        $layoutPath = __DIR__ . "/../../../layouts/$layoutName.php";
        
        // دعم البحث عن المسارات الديناميكية و requestType
        $contentPaths = [
            __DIR__ . "/../../../web/$contentFile.php", // المسار العادي
            __DIR__ . "/../../../web/{$contentFile}_dynamic.php", // المسار الديناميكي
            __DIR__ . "/../../../web/{$contentFile}_request_$requestType.php" // المسار مع نوع الطلب
        ];
        
        $foundContentPath = null;
        foreach ($contentPaths as $path) {
            if (file_exists($path)) {
                $foundContentPath = $path;
                break;
            }
        }
        
        if (!file_exists($layoutPath)) {
            die("❌ Error: Layout file '$layoutName.php' not found at '$layoutPath'!");
        }
        if (!$foundContentPath) {
            die("❌ Error: Content file for '$contentFile' not found in expected paths!");
        }
        
        extract($data);
        include $layoutPath;
    }

    public static function section($section) {
        return self::$sections[$section] ?? "❌ Error: Section '$section' not found!";
    }
}

<?php

/**
 * A class for managing layouts and sections.
 */
class Layout
{
    /**
     * An array of defined sections.
     *
     * @var array
     */
    private static $sections = [];

    /**
     * The name of the current section being captured.
     *
     * @var string|null
     */
    private static $currentSection = null;

    /**
     * Starts a new section.
     *
     * @param string $section The name of the section.
     */
    public static function start($section)
    {
        if (self::$currentSection !== null) {
            exit('❌ Error: Nested sections are not allowed!');
        }
        self::$currentSection = $section;
        ob_start();
    }

    /**
     * Ends the current section.
     */
    public static function end()
    {
        if (self::$currentSection === null) {
            exit('❌ Error: No section has been started!');
        }
        self::$sections[self::$currentSection] = ob_get_clean();
        self::$currentSection = null;
    }

    /**
     * Renders a layout with a content file.
     *
     * @param string $layoutName    The name of the layout file.
     * @param string $contentFile   The name of the content file.
     * @param string $requestType   The request type (e.g., 'GET', 'POST').
     * @param array  $data          An array of data to be extracted and used in the layout and content files.
     */
    public static function render($layoutName, $contentFile, $requestType = 'GET', $data = [])
    {
        global $Ahmed;

        $layoutPath = __DIR__."/../../../../layouts/$layoutName.ahmed.php";

        $contentPaths = [
            __DIR__."/../../../../web/$contentFile.ahmed.php",
            __DIR__."/../../../../web/{$contentFile}_dynamic.ahmed.php",
            __DIR__."/../../../../web/{$contentFile}_request_$requestType.ahmed.php",
            __DIR__."/../../../../web/{$contentFile}_dynamic_api.ahmed.php",
            __DIR__."/../../../../web/{$contentFile}_request_{$requestType}_api.ahmed.php",
        ];

        $foundContentPath = null;
        foreach ($contentPaths as $path) {
            if (file_exists($path)) {
                $foundContentPath = $path;
                break;
            }
        }

        if (!file_exists($layoutPath)) {
            exit("❌ Error: Layout file '$layoutName.php' not found at '$layoutPath'!");
        }
        if (!$foundContentPath) {
            exit("❌ Error: Content file for '$contentFile' not found in expected paths!");
        }

        extract($data);
        echo $Ahmed->render($layoutPath);
    }

    /**
     * Gets the content of a section.
     *
     * @param string $section The name of the section.
     *
     * @return string The content of the section.
     */
    public static function section($section)
    {
        return self::$sections[$section] ?? "❌ Error: Section '$section' not found!";
    }
}

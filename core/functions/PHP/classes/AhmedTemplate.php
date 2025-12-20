<?php

/**
 * A simple, lightweight template engine for rendering PHP templates with custom syntax.
 *
 * This class provides a basic but powerful template engine that parses template files,
 * replacing custom directives (e.g., `{{ $variable }}`, `@if(...)`, `@foreach(...)`)
 * with standard PHP code, which is then executed to generate the final HTML output.
 * It is the core of the view rendering system in the INEX SPA framework.
 */
class AhmedTemplate
{
    /**
     * Renders a template file with the given data and returns the output as a string.
     *
     * This method reads a template file, parses its content to replace custom
     * template syntax with executable PHP, and then evaluates the result using
     * output buffering. The provided data array is extracted into local variables
     * that are accessible within the template's scope.
     *
     * @param string $template The full path to the template file to be rendered.
     * @param array  $data     An associative array of data to be extracted into variables
     *                         (e.g., `['name' => 'John']` becomes `$name`).
     *
     * @throws Exception If the specified template file does not exist.
     *
     * @return string The fully rendered HTML content.
     */
    public function render($template, $data = [])
    {
        $templateFile = $template;
        if (!file_exists($templateFile)) {
            throw new Exception("Template '$template' not found");
        }

        $content = file_get_contents($templateFile);
        $parsedContent = $this->parse($content);

        extract($data);
        ob_start();
        eval('?>'.$parsedContent);

        return ob_get_clean();
    }

    /**
     * Parses a raw template string, replacing custom Ahmed Template directives with executable PHP code.
     *
     * This method uses a series of regular expressions to find and replace all supported
     * template tags. This includes:
     * - Variable echoing (e.g., `{{ $name }}`)
     * - Control structures (e.g., `@if`, `@foreach`, `@for`, `@while`)
     * - Conditional checks (e.g., `@isset`, `@empty`)
     * - Layout and section directives (e.g., `@section`, `@render`)
     * - Helper function calls (e.g., `@getLang`, `@getEnv`)
     * - Raw PHP execution blocks (e.g., `@php ... @endphp`)
     *
     * @param string $content The raw string content of the template file.
     *
     * @return string The template content with all custom syntax converted into
     *                standard PHP, ready for evaluation.
     */
    protected function parse($content)
    {
        $patterns = [
            // Variable output
            '/{{\s*(.+?)\s*}}/' => '<?= htmlentities($1) ?>',

            // Control structures
            '/@if\s*\((.+?)\)/'      => '<?php if ($1): ?>',
            '/@elseif\s*\((.+?)\)/'  => '<?php elseif ($1): ?>',
            '/@else/'                => '<?php else: ?>',
            '/@endif/'               => '<?php endif; ?>',
            '/@foreach\s*\((.+?)\)/' => '<?php foreach ($1): ?>',
            '/@endforeach/'          => '<?php endforeach; ?>',
            '/@for\((.+?)\)/'        => '<?php for ($1): ?>',
            '/@endfor/'              => '<?php endfor; ?>',
            '/@while\((.+?)\)/'      => '<?php while ($1): ?>',
            '/@endwhile/'            => '<?php endwhile; ?>',
            '/@do/'                  => '<?php do { ?>',
            '/@whileCond\((.+?)\)/'  => '<?php } while ($1); ?>',
            '/@switch\((.+?)\)/'     => '<?php switch ($1): ?>',
            '/@case\((.+?)\)/'       => '<?php case $1: ?>',
            '/@default/'             => '<?php default: ?>',
            '/@endswitch/'           => '<?php endswitch; ?>',
            '/@break/'               => '<?php break; ?>',
            '/@continue/'            => '<?php continue; ?>',

            // Conditional checks
            '/@isset\((.+?)\)/'  => '<?php if (isset($1)): ?>',
            '/@endisset/'        => '<?php endif; ?>',
            '/@empty\((.+?)\)/'  => '<?php if (empty($1)): ?>',
            '/@endempty/'        => '<?php endif; ?>',
            '/@unless\((.+?)\)/' => '<?php if (!($1)): ?>',
            '/@endunless/'       => '<?php endif; ?>',

            // Functions and helpers
            '/@getLang\("(.+?)"\)/'      => '<?= Language::get("$1") ?>',
            '/@getEnv\("(.+?)"\)/'       => '<?php echo getEnvValue("$1"); ?>',
            '/@include\("(.+?)"\)/'      => '<?php include "$1"; ?>',
            '/@require\("(.+?)"\)/'      => '<?php require "$1"; ?>',
            '/@runDB()/'                 => '<?php runDB(); ?>',
            '/@generateSitemap()/'       => '<?php SitemapGenerator::generate(); ?>',
            '/@checkRateLimit\((.+?)\)/' => '<?php RateLimiter::check($1); ?>',
            '/@validateCsrf()/'          => '<?php validateCsrfToken(); ?>',

            // Layout and sections
            '/@section\("(.+?)"\)/'                               => '<?php Layout::start("$1"); ?>',
            '/@endSection/'                                       => '<?php Layout::end(); ?>',
            '/@render\("(.+?)",\s*"(.+?)",\s*"(.+?)",\s*(.*?)\)/' => '<?php Layout::render("$1", "$2", "$3", $4); ?>',
            '/@getSection\("(.+?)"\)/'                            => '<?= Layout::section("$1") ?>',

            // Variables and data manipulation
            '/@set\("(.+?)",\s*(.+?)\)/'    => '<?php $$1 = $2; ?>',
            '/@define\("(.+?)",\s*(.+?)\)/' => '<?php $1 = $2; ?>',
            '/@var\("(.+?)"\)/'             => '<?= $$1 ?>',
            '/@postData\("(.+?)"\)/'        => '<?= $_POST["$1"] ?>',
            '/@getData\("(.+?)"\)/'         => '<?= $_GET["$1"] ?? "" ?>',
            '/@toJson\((.+?)\)/'            => '<?= json_encode($1) ?>',
            '/@fromJson\((.+?)\)/'          => '<?= json_decode($1, true) ?>',
            '/@jsonFile\("(.+?)"\)/'        => '<?= json_decode(file_get_contents("$1"), true) ?>',

            // String and number functions
            '/@strtoupper\("(.+?)"\)/'             => '<?= strtoupper("$1") ?>',
            '/@strtolower\("(.+?)"\)/'             => '<?= strtolower("$1") ?>',
            '/@ucfirst\("(.+?)"\)/'                => '<?= ucfirst("$1") ?>',
            '/@strlen\((.+?)\)/'                   => '<?= strlen($1) ?>',
            '/@trim\((.+?)\)/'                     => '<?= trim($1) ?>',
            '/@substr\((.+?),\s*(.+?),\s*(.+?)\)/' => '<?= substr($1, $2, $3) ?>',
            '/@escape\((.+?)\)/'                   => '<?= htmlspecialchars($1, ENT_QUOTES, "UTF-8") ?>',
            '/@number_format\((.+?),\s*(.+?)\)/'   => '<?= number_format($1, $2) ?>',
            '/@date\("(.+?)",\s*(.+?)\)/'          => '<?= date("$1", $2) ?>',

            // Debugging
            '/@dump\((.+?)\)/' => '<?php var_dump($1); ?>',
            '/@dd\((.+?)\)/'   => '<?php die(var_dump($1)); ?>',

            // Raw PHP
            '/@php/'               => '<?php ',
            '/@endphp/'            => ' ?>',
            '/@phpCode\((.*?)\)/s' => '<?php $1 ?>',

            // Comments
            '/{{--(.*?)--}}/s' => '<?php /* $1 */ ?>',

            // Language and session
            '/@setLang\("(.+?)"\)/'                => '<?php Language::set("$1"); ?>',
            '/@makeSession\("(.+?)",\s*"(.+?)"\)/' => '<?php Session::make("$1", "$2"); ?>',
            '/@getSession\("(.+?)"\)/'             => '<?= Session::get("$1") ?>',
            '/@deleteSession\("(.+?)"\)/'          => '<?php Session::delete("$1"); ?>',

            // Cookies
            '/@setCookie\("(.+?)",\s*"(.+?)",\s*(.+?)\)/' => '<?php CookieManager::set("$1", "$2", $3); ?>',
            '/@getCookie\("(.+?)"\)/'                     => '<?= CookieManager::get("$1") ?>',
            '/@existsCookie\("(.+?)"\)/'                  => '<?= CookieManager::exists("$1") ? "true" : "false" ?>',
            '/@deleteCookie\("(.+?)"\)/'                  => '<?php CookieManager::delete("$1"); ?>',
            '/@getAllCookies()/'                          => '<?= json_encode(CookieManager::getAll()) ?>',

            // Cache
            '/@setCache\("(.+?)",\s*"(.+?)",\s*(.+?)\)/' => '<?php setCache("$1", "$2", $3); ?>',
            '/@getCache\("(.+?)"\)/'                     => '<?php getCache("$1") ?>',
            '/@updateCache\("(.+?)",\s*"(.+?)"\)/'       => '<?php updateCache("$1", "$2"); ?>',
            '/@deleteCache\("(.+?)"\)/'                  => '<?php deleteCache("$1"); ?>',

            // AI
            '/@useGemini\((.+?),\s*(.*?),\s*(.*?),\s*(.+?),\s*(.+?),\s*(.+?),\s*(.+?)\)/' => '<?= json_encode(useGemini($1, $2, $3, $4, $5, $6, $7)) ?>',

            // Generic function calls (must be last)
            '/@([a-zA-Z_][a-zA-Z0-9_]*)\((.*?)\)/' => '<?= $1($2) ?>',
            '/@([a-zA-Z_][a-zA-Z0-9_]*)/'          => '<?= $1() ?>',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $content);
    }
}

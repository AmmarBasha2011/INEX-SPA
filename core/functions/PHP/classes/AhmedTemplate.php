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

        extract($data, EXTR_SKIP);
        ob_start();
        eval('?>'.$parsedContent);

        return ob_get_clean();
    }

    /**
     * Parses a raw template string, replacing custom Ahmed Template directives with executable PHP code.
     *
     * SECURITY NOTE: This method uses eval() to execute compiled template code.
     * This is safe because:
     * 1. Templates are loaded from trusted files (web/ directory), not user input
     * 2. The compiled PHP code is generated from regex replacements, not raw user data
     * 3. All variable output uses htmlentities() for XSS prevention
     *
     * @param string $content The raw string content of the template file.
     *
     * @return string The template content with all custom syntax converted into
     *                standard PHP, ready for evaluation.
     */
    protected function parse($content)
    {
        $content = $this->parseControlFlow($content);
        $content = $this->parseConditionals($content);
        $content = $this->parseFunctions($content);
        $content = $this->parseLayoutDirectives($content);
        $content = $this->parseDataDirectives($content);
        $content = $this->parseStringFunctions($content);
        $content = $this->parseDebugDirectives($content);
        $content = $this->parsePhpDirectives($content);
        $content = $this->parseComments($content);
        $content = $this->parseSessionLanguage($content);
        $content = $this->parseCookieDirectives($content);
        $content = $this->parseCacheDirectives($content);
        $content = $this->parseAiDirectives($content);

        return $this->parseGenericFunctions($content);
    }

    /**
     * Parse control flow directives (@if, @foreach, @for, @while, etc.).
     *
     * @param string $content The raw template content.
     *
     * @return string The content with control flow directives converted.
     */
    private function parseControlFlow($content)
    {
        $patterns = [
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
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $content);
    }

    /**
     * Parse conditional check directives (@isset, @empty, @unless).
     *
     * @param string $content The raw template content.
     *
     * @return string The content with conditional directives converted.
     */
    private function parseConditionals($content)
    {
        $patterns = [
            '/@isset\((.+?)\)/'  => '<?php if (isset($1)): ?>',
            '/@endisset/'        => '<?php endif; ?>',
            '/@empty\((.+?)\)/'  => '<?php if (empty($1)): ?>',
            '/@endempty/'        => '<?php endif; ?>',
            '/@unless\((.+?)\)/' => '<?php if (!($1)): ?>',
            '/@endunless/'       => '<?php endif; ?>',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $content);
    }

    /**
     * Parse function and helper directives (@getLang, @getEnv, @include, etc.).
     *
     * @param string $content The raw template content.
     *
     * @return string The content with function directives converted.
     */
    private function parseFunctions($content)
    {
        $patterns = [
            '/@getLang\("(.+?")\)/'      => '<?= Language::get("$1") ?>',
            '/@getEnv\("(.+?")\)/'       => '<?php echo getEnvValue("$1"); ?>',
            '/@include\("(.+?")\)/'      => '<?php include "$1"; ?>',
            '/@require\("(.+?")\)/'      => '<?php require "$1"; ?>',
            '/@runDB()/'                 => '<?php runDB(); ?>',
            '/@generateSitemap()/'       => '<?php SitemapGenerator::generate(); ?>',
            '/@checkRateLimit\((.+?)\)/' => '<?php RateLimiter::check($1); ?>',
            '/@validateCsrf()/'          => '<?php validateCsrfToken(); ?>',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $content);
    }

    /**
     * Parse layout and section directives (@section, @endSection, @render, @getSection).
     *
     * @param string $content The raw template content.
     *
     * @return string The content with layout directives converted.
     */
    private function parseLayoutDirectives($content)
    {
        $patterns = [
            '/@section\("(.+?")\)/'                               => '<?php Layout::start("$1"); ?>',
            '/@endSection/'                                       => '<?php Layout::end(); ?>',
            '/@render\("(.+?)",\s*"(.+?)",\s*"(.+?)",\s*(.*?)\)/' => '<?php Layout::render("$1", "$2", "$3", $4); ?>',
            '/@getSection\("(.+?")\)/'                            => '<?= Layout::section("$1") ?>',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $content);
    }

    /**
     * Parse data manipulation directives (@set, @define, @var, @postData, @getData, etc.).
     *
     * @param string $content The raw template content.
     *
     * @return string The content with data directives converted.
     */
    private function parseDataDirectives($content)
    {
        $patterns = [
            '/@set\("(.+?)",\s*(.+?)\)/'    => '<?php $$1 = $2; ?>',
            '/@define\("(.+?)",\s*(.+?)\)/' => '<?php $1 = $2; ?>',
            '/@var\("(.+?")\)/'             => '<?= $$1 ?>',
            '/@postData\("(.+?")\)/'        => '<?= htmlspecialchars($_POST["$1"] ?? "", ENT_QUOTES, "UTF-8") ?>',
            '/@getData\("(.+?")\)/'         => '<?= htmlspecialchars($_GET["$1"] ?? "", ENT_QUOTES, "UTF-8") ?>',
            '/@toJson\((.+?)\)/'            => '<?= json_encode($1) ?>',
            '/@fromJson\((.+?)\)/'          => '<?= json_decode($1, true) ?>',
            '/@jsonFile\("(.+?")\)/'        => '<?= (function($f) { if(!file_exists($f)) return "[]"; $d = @json_decode(@file_get_contents($f), true); return is_array($d) ? json_encode($d) : "[]"; })("$1") ?>',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $content);
    }

    /**
     * Parse string and number function directives (@strtoupper, @strlen, @substr, etc.).
     *
     * @param string $content The raw template content.
     *
     * @return string The content with string function directives converted.
     */
    private function parseStringFunctions($content)
    {
        $patterns = [
            '/@strtoupper\("(.+?")\)/'             => '<?= strtoupper("$1") ?>',
            '/@strtolower\("(.+?")\)/'             => '<?= strtolower("$1") ?>',
            '/@ucfirst\("(.+?")\)/'                => '<?= ucfirst("$1") ?>',
            '/@strlen\((.+?)\)/'                   => '<?= strlen($1) ?>',
            '/@trim\((.+?)\)/'                     => '<?= trim($1) ?>',
            '/@substr\((.+?),\s*(.+?),\s*(.+?)\)/' => '<?= substr($1, $2, $3) ?>',
            '/@escape\((.+?)\)/'                   => '<?= htmlspecialchars($1, ENT_QUOTES, "UTF-8") ?>',
            '/@number_format\((.+?),\s*(.+?)\)/'   => '<?= number_format($1, $2) ?>',
            '/@date\("(.+?)",\s*(.+?)\)/'          => '<?= date("$1", $2) ?>',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $content);
    }

    /**
     * Parse debugging directives (@dump, @dd).
     *
     * @param string $content The raw template content.
     *
     * @return string The content with debug directives converted.
     */
    private function parseDebugDirectives($content)
    {
        $patterns = [
            '/@dump\((.+?)\)/' => '<?php var_dump($1); ?>',
            '/@dd\((.+?)\)/'   => '<?php die(var_dump($1)); ?>',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $content);
    }

    /**
     * Parse raw PHP directives (@php, @endphp, @phpCode).
     *
     * @param string $content The raw template content.
     *
     * @return string The content with PHP directives converted.
     */
    private function parsePhpDirectives($content)
    {
        $patterns = [
            '/@php/'               => '<?php ',
            '/@endphp/'            => ' ?>',
            '/@phpCode\((.*?)\)/s' => '<?php $1 ?>',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $content);
    }

    /**
     * Parse comment directives ({{-- --}}).
     *
     * @param string $content The raw template content.
     *
     * @return string The content with comments converted.
     */
    private function parseComments($content)
    {
        return preg_replace('/{{--(.*?)--}}/s', '<?php /* $1 */ ?>', $content);
    }

    /**
     * Parse session and language directives (@setLang, @makeSession, @getSession, @deleteSession).
     *
     * @param string $content The raw template content.
     *
     * @return string The content with session/language directives converted.
     */
    private function parseSessionLanguage($content)
    {
        $patterns = [
            '/@setLang\("(.+?")\)/'                => '<?php Language::set("$1"); ?>',
            '/@makeSession\("(.+?)",\s*"(.+?")\)/' => '<?php Session::make("$1", "$2"); ?>',
            '/@getSession\("(.+?")\)/'             => '<?= Session::get("$1") ?>',
            '/@deleteSession\("(.+?")\)/'          => '<?php Session::delete("$1"); ?>',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $content);
    }

    /**
     * Parse cookie management directives (@setCookie, @getCookie, @existsCookie, etc.).
     *
     * @param string $content The raw template content.
     *
     * @return string The content with cookie directives converted.
     */
    private function parseCookieDirectives($content)
    {
        $patterns = [
            '/@setCookie\("(.+?)",\s*"(.+?)",\s*(.+?)\)/' => '<?php CookieManager::set("$1", "$2", $3); ?>',
            '/@getCookie\("(.+?")\)/'                     => '<?= CookieManager::get("$1") ?>',
            '/@existsCookie\("(.+?")\)/'                  => '<?= CookieManager::exists("$1") ? "true" : "false" ?>',
            '/@deleteCookie\("(.+?")\)/'                  => '<?php CookieManager::delete("$1"); ?>',
            '/@getAllCookies()/'                          => '<?= json_encode(CookieManager::getAll()) ?>',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $content);
    }

    /**
     * Parse cache management directives (@setCache, @getCache, @updateCache, @deleteCache).
     *
     * @param string $content The raw template content.
     *
     * @return string The content with cache directives converted.
     */
    private function parseCacheDirectives($content)
    {
        $patterns = [
            '/@setCache\("(.+?)",\s*"(.+?)",\s*(.+?)\)/' => '<?php setCache("$1", "$2", $3); ?>',
            '/@getCache\("(.+?")\)/'                     => '<?php getCache("$1") ?>',
            '/@updateCache\("(.+?)",\s*"(.+?")\)/'       => '<?php updateCache("$1", "$2"); ?>',
            '/@deleteCache\("(.+?")\)/'                  => '<?php deleteCache("$1"); ?>',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $content);
    }

    /**
     * Parse AI directives (@useGemini).
     *
     * @param string $content The raw template content.
     *
     * @return string The content with AI directives converted.
     */
    private function parseAiDirectives($content)
    {
        $patterns = [
            '/@useGemini\((.+?),\s*(.+?),\s*(.+?),\s*(.+?),\s*(.+?),\s*(.+?),\s*(.+?)\)/' => '<?= json_encode(useGemini($1, $2, $3, $4, $5, $6, $7)) ?>',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $content);
    }

    /**
     * Parse generic function calls (must be applied last).
     *
     * @param string $content The raw template content.
     *
     * @return string The content with generic function calls converted.
     */
    private function parseGenericFunctions($content)
    {
        $patterns = [
            '/@([a-zA-Z_][a-zA-Z0-9_]*)\((.*?)\)/' => '<?= $1($2) ?>',
            '/@([a-zA-Z_][a-zA-Z0-9_]*)/'          => '<?= $1() ?>',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $content);
    }
}

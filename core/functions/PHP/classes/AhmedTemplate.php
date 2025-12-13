<?php

/**
 * Ahmed Template Engine
 *
 * A lightweight yet powerful template engine for PHP that compiles custom template
 * syntax into plain PHP code. It supports variables, control structures, includes,
 * layouts, and more, making template creation clean and efficient.
 *
 * @package INEX\Core
 */
class AhmedTemplate
{
    /**
     * Renders a template file with the provided data.
     *
     * This method reads a template file, parses its custom syntax into executable
     * PHP code, and then evaluates it, capturing the output. It extracts the
     * provided data array into variables accessible within the template.
     *
     * @param string $template The file path to the template to be rendered.
     * @param array  $data     An associative array of data to be made available to the template.
     *                         Keys become variable names.
     *
     * @return string The fully rendered HTML or text content.
     *
     * @throws \Exception If the specified template file does not exist.
     */
    public function render($template, $data = [])
    {
        $templateFile = $template;
        if (!file_exists($templateFile)) {
            throw new Exception("Template '$template' not found");
        }

        $content = file_get_contents($templateFile);
        $parsedContent = $this->parse($content);

        // Extract the data array into the current symbol table
        extract($data);

        // Start output buffering to capture the evaluated template
        ob_start();
        // Evaluate the parsed PHP code
        eval('?>'.$parsedContent);

        // Return the captured output
        return ob_get_clean();
    }

    /**
     * Parses the raw template content into executable PHP code.
     *
     * This method uses a series of regular expressions to find and replace the
     * custom Ahmed Template syntax (e.g., {{ $variable }}, @if, @foreach) with
     * their corresponding native PHP code constructs.
     *
     * @param string $content The raw string content of the template file.
     *
     * @return string The template content with all custom syntax compiled into PHP.
     */
    protected function parse($content)
    {
        $patterns = [
            // {{ $variable }} for safe HTML output
            '/{{\s*(.+?)\s*}}/' => '<?= htmlentities($1) ?>',

            // Control structures: @if, @foreach, etc.
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

            // Conditional checks: @isset, @empty
            '/@isset\((.+?)\)/'  => '<?php if (isset($1)): ?>',
            '/@endisset/'        => '<?php endif; ?>',
            '/@empty\((.+?)\)/'  => '<?php if (empty($1)): ?>',
            '/@endempty/'        => '<?php endif; ?>',
            '/@unless\((.+?)\)/' => '<?php if (!($1)): ?>',
            '/@endunless/'       => '<?php endif; ?>',

            // Built-in framework function helpers
            '/@getLang\("(.+?)"\)/'      => '<?= Language::get("$1") ?>',
            '/@getEnv\("(.+?)"\)/'       => '<?php echo getEnvValue("$1"); ?>',
            '/@include\("(.+?)"\)/'      => '<?php include "$1"; ?>',
            '/@require\("(.+?)"\)/'      => '<?php require "$1"; ?>',
            '/@runDB()/'                 => '<?php runDB(); ?>',
            '/@generateSitemap()/'       => '<?php SitemapGenerator::generate(); ?>',
            '/@checkRateLimit\((.+?)\)/' => '<?php RateLimiter::check($1); ?>',
            '/@validateCsrf()/'          => '<?php validateCsrfToken(); ?>',

            // Layout and section directives
            '/@section\("(.+?)"\)/'                               => '<?php Layout::start("$1"); ?>',
            '/@endSection/'                                       => '<?php Layout::end(); ?>',
            '/@render\("(.+?)",\s*"(.+?)",\s*"(.+?)",\s*(.*?)\)/' => '<?php Layout::render("$1", "$2", "$3", $4); ?>',
            '/@getSection\("(.+?)"\)/'                            => '<?= Layout::section("$1") ?>',

            // Variable and data handling
            '/@set\("(.+?)",\s*(.+?)\)/'    => '<?php $$1 = $2; ?>',
            '/@define\("(.+?)",\s*(.+?)\)/' => '<?php $1 = $2; ?>',
            '/@var\("(.+?)"\)/'             => '<?= $$1 ?>',
            '/@postData\("(.+?)"\)/'        => '<?= $_POST["$1"] ?>',
            '/@getData\("(.+?)"\)/'         => '<?= $_GET["$1"] ?? "" ?>',
            '/@toJson\((.+?)\)/'            => '<?= json_encode($1) ?>',
            '/@fromJson\((.+?)\)/'          => '<?= json_decode($1, true) ?>',
            '/@jsonFile\("(.+?)"\)/'        => '<?= json_decode(file_get_contents("$1"), true) ?>',

            // String and number formatting functions
            '/@strtoupper\("(.+?)"\)/'             => '<?= strtoupper("$1") ?>',
            '/@strtolower\("(.+?)"\)/'             => '<?= strtolower("$1") ?>',
            '/@ucfirst\("(.+?)"\)/'                => '<?= ucfirst("$1") ?>',
            '/@strlen\((.+?)\)/'                   => '<?= strlen($1) ?>',
            '/@trim\((.+?)\)/'                     => '<?= trim($1) ?>',
            '/@substr\((.+?),\s*(.+?),\s*(.+?)\)/' => '<?= substr($1, $2, $3) ?>',
            '/@escape\((.+?)\)/'                   => '<?= htmlspecialchars($1, ENT_QUOTES, "UTF-8") ?>',
            '/@number_format\((.+?),\s*(.+?)\)/'   => '<?= number_format($1, $2) ?>',
            '/@date\("(.+?)",\s*(.+?)\)/'          => '<?= date("$1", $2) ?>',

            // Debugging helpers
            '/@dump\((.+?)\)/' => '<?php var_dump($1); ?>',
            '/@dd\((.+?)\)/'   => '<?php die(var_dump($1)); ?>',

            // Raw PHP execution blocks
            '/@php/'               => '<?php ',
            '/@endphp/'            => ' ?>',
            '/@phpCode\((.*?)\)/s' => '<?php $1 ?>',

            // Template comments
            '/{{--(.*?)--}}/s' => '<?php /* $1 */ ?>',

            // Language and session management
            '/@setLang\("(.+?)"\)/'                => '<?php Language::set("$1"); ?>',
            '/@makeSession\("(.+?)",\s*"(.+?)"\)/' => '<?php Session::make("$1", "$2"); ?>',
            '/@getSession\("(.+?)"\)/'             => '<?= Session::get("$1") ?>',
            '/@deleteSession\("(.+?)"\)/'          => '<?php Session::delete("$1"); ?>',

            // Cookie management
            '/@setCookie\("(.+?)",\s*"(.+?)",\s*(.+?)\)/' => '<?php CookieManager::set("$1", "$2", $3); ?>',
            '/@getCookie\("(.+?)"\)/'                     => '<?= CookieManager::get("$1") ?>',
            '/@existsCookie\("(.+?)"\)/'                  => '<?= CookieManager::exists("$1") ? "true" : "false" ?>',
            '/@deleteCookie\("(.+?)"\)/'                  => '<?php CookieManager::delete("$1"); ?>',
            '/@getAllCookies()/'                          => '<?= json_encode(CookieManager::getAll()) ?>',

            // Cache management
            '/@setCache\("(.+?)",\s*"(.+?)",\s*(.+?)\)/' => '<?php setCache("$1", "$2", $3); ?>',
            '/@getCache\("(.+?)"\)/'                     => '<?php getCache("$1") ?>',
            '/@updateCache\("(.+?)",\s*"(.+?)"\)/'       => '<?php updateCache("$1", "$2"); ?>',
            '/@deleteCache\("(.+?)"\)/'                  => '<?php deleteCache("$1"); ?>',

            // AI integration
            '/@useGemini\((.+?),\s*(.*?),\s*(.*?),\s*(.+?),\s*(.+?),\s*(.+?),\s*(.+?)\)/' => '<?= json_encode(useGemini($1, $2, $3, $4, $5, $6, $7)) ?>',

            // Generic function calls (should be last to avoid conflicts)
            '/@([a-zA-Z_][a-zA-Z0-9_]*)\((.*?)\)/' => '<?= $1($2) ?>',
            '/@([a-zA-Z_][a-zA-Z0-9_]*)/'          => '<?= $1() ?>',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $content);
    }
}

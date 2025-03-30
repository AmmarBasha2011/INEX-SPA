<?php
class AhmedTemplate {
    public function render($template, $data = []) {
        $templateFile = $template;
        if (!file_exists($templateFile)) {
            throw new Exception("Template '$template' not found");
        }
        
        $content = file_get_contents($templateFile);
        $parsedContent = $this->parse($content);
        
        extract($data);
        ob_start();
        eval('?>' . $parsedContent);
        return ob_get_clean();
    }
    
    protected function parse($content) {
        $patterns = [
            '/{{\s*(.+?)\s*}}/' => '<?= htmlentities($1) ?>',
            '/@if\s*\((.+?)\)/' => '<?php if ($1): ?>',
            '/@elseif\s*\((.+?)\)/' => '<?php elseif ($1): ?>',
            '/@else/' => '<?php else: ?>',
            '/@endif/' => '<?php endif; ?>',
            '/@foreach\s*\((.+?)\)/' => '<?php foreach ($1): ?>',
            '/@endforeach/' => '<?php endforeach; ?>',
            '/@getLang\("(.+?)"\)/' => '<?= Language::get("$1") ?>',
            '/@getEnv\("(.+?)"\)/' => '<?php echo getEnvValue("$1"); ?>',
            '/@section\("(.+?)"\)/' => '<?php Layout::start("$1"); ?>',
            '/@endSection/' => '<?php Layout::end(); ?>',
            '/@render\("(.+?)",\s*"(.+?)",\s*"(.+?)",\s*(.*?)\)/' => '<?php Layout::render("$1", "$2", "$3", $4); ?>',
            '/@include\("(.+?)"\)/' => '<?php include "$1"; ?>',
            '/@switch\((.+?)\)/' => '<?php switch ($1): ?>',
            '/@case\((.+?)\)/' => '<?php case $1: ?>',
            '/@default/' => '<?php default: ?>',
            '/@endswitch/' => '<?php endswitch; ?>',
            '/@for\((.+?)\)/' => '<?php for ($1): ?>',
            '/@endfor/' => '<?php endfor; ?>',
            '/@while\((.+?)\)/' => '<?php while ($1): ?>',
            '/@endwhile/' => '<?php endwhile; ?>',
            '/@set\("(.+?)",\s*(.+?)\)/' => '<?php $$1 = $2; ?>',
            '/@dump\((.+?)\)/' => '<?php var_dump($1); ?>',
            '/@dd\((.+?)\)/' => '<?php die(var_dump($1)); ?>',
            '/@isset\((.+?)\)/' => '<?php if (isset($1)): ?>',
            '/@endisset/' => '<?php endif; ?>',
            '/@empty\((.+?)\)/' => '<?php if (empty($1)): ?>',
            '/@endempty/' => '<?php endif; ?>',
            '/@php/' => '<?php ',
            '/@endphp/' => ' ?>',
            '/@unless\((.+?)\)/' => '<?php if (!($1)): ?>',
            '/@endunless/' => '<?php endif; ?>',
            '/@break/' => '<?php break; ?>',
            '/@continue/' => '<?php continue; ?>',
            '/@strtoupper\("(.+?)"\)/' => '<?= strtoupper("$1") ?>',
            '/@strtolower\("(.+?)"\)/' => '<?= strtolower("$1") ?>',
            '/@ucfirst\("(.+?)"\)/' => '<?= ucfirst("$1") ?>',
            '/@number_format\((.+?),\s*(.+?)\)/' => '<?= number_format($1, $2) ?>',
            '/@date\("(.+?)",\s*(.+?)\)/' => '<?= date("$1", $2) ?>',
            '/@runDB()/' => '<?php runDB(); ?>',
            '/@generateSitemap()/' => '<?php SitemapGenerator::generate(); ?>',
            '/@checkRateLimit\((.+?)\)/' => '<?php RateLimiter::check($1); ?>',
            '/@validateCsrf()/' => '<?php validateCsrfToken(); ?>',
            '/@define\("(.+?)",\s*(.+?)\)/' => '<?php $1 = $2; ?>',
            '/{{--(.*?)--}}/s' => '<?php /* $1 */ ?>',
            '/@escape\((.+?)\)/' => '<?= htmlspecialchars($1, ENT_QUOTES, "UTF-8") ?>',
            '/@substr\((.+?),\s*(.+?),\s*(.+?)\)/' => '<?= substr($1, $2, $3) ?>',
            '/@jsonFile\("(.+?)"\)/' => '<?= json_decode(file_get_contents("$1"), true) ?>',
            '/@require\("(.+?)"\)/' => '<?php require "$1"; ?>',
            '/@do/' => '<?php do { ?>',
            '/@whileCond\((.+?)\)/' => '<?php } while ($1); ?>',
            '/@setLang\("(.+?)"\)/' => '<?php Language::set("$1"); ?>',
            '/@var\("(.+?)"\)/' => '<?= $$1 ?>',
            '/@phpCode\((.*?)\)/s' => '<?php $1 ?>',
            '/@postData\("(.+?)"\)/' => '<?= $_POST["$1"] ?>',
            '/@toJson\((.+?)\)/' => '<?= json_encode($1) ?>',
            '/@fromJson\((.+?)\)/' => '<?= json_decode($1, true) ?>',
            '/@strlen\((.+?)\)/' => '<?= strlen($1) ?>',
            '/@trim\((.+?)\)/' => '<?= trim($1) ?>',
            '/@getData\("(.+?)"\)/' => '<?= $_GET["$1"] ?? "" ?>',
            '/@setCookie\("(.+?)",\s*"(.+?)",\s*(.+?)\)/' => '<?php CookieManager::set("$1", "$2", $3); ?>',
            '/@getCookie\("(.+?)"\)/' => '<?= CookieManager::get("$1") ?>',
            '/@existsCookie\("(.+?)"\)/' => '<?= CookieManager::exists("$1") ? "true" : "false" ?>',
            '/@deleteCookie\("(.+?)"\)/' => '<?php CookieManager::delete("$1"); ?>',
            '/@getAllCookies()/' => '<?= json_encode(CookieManager::getAll()) ?>',
            '/@makeSession\("(.+?)",\s*"(.+?)"\)/' => '<?php Session::make("$1", "$2"); ?>',
            '/@getSession\("(.+?)"\)/' => '<?= Session::get("$1") ?>',
            '/@deleteSession\("(.+?)"\)/' => '<?php Session::delete("$1"); ?>',
            '/@useGemini\((.+?),\s*(.*?),\s*(.*?),\s*(.+?),\s*(.+?),\s*(.+?),\s*(.+?)\)/' => '<?= json_encode(useGemini($1, $2, $3, $4, $5, $6, $7)) ?>',
            '/@setCache\("(.+?)",\s*"(.+?)",\s*(.+?)\)/' => '<?php setCache("$1", "$2", $3); ?>',
            '/@getCache\("(.+?)"\)/' => '<?php getCache("$1") ?>',
            '/@updateCache\("(.+?)",\s*"(.+?)"\)/' => '<?php updateCache("$1", "$2"); ?>',
            '/@deleteCache\("(.+?)"\)/' => '<?php deleteCache("$1"); ?>',
            '/@getSection\("(.+?)"\)/' => '<?= Layout::section("$1") ?>',
            '/@([a-zA-Z_][a-zA-Z0-9_]*)\((.*?)\)/' => '<?= $1($2) ?>',
            '/@([a-zA-Z_][a-zA-Z0-9_]*)/' => '<?= $1() ?>',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $content);
    }
}
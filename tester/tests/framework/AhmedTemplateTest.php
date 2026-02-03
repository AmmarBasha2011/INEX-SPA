<?php
// tester/tests/framework/AhmedTemplateTest.php

class AhmedTemplateTester extends AhmedTemplate {
    public function publicParse($content) {
        return $this->parse($content);
    }
}

$engine = new AhmedTemplateTester();

runTest('AhmedTemplate - variable output', function() use ($engine) {
    $parsed = $engine->publicParse('{{ $name }}');
    assertTrue(strpos($parsed, 'htmlentities($name)') !== false);
});

runTest('AhmedTemplate - if statement', function() use ($engine) {
    $parsed = $engine->publicParse('@if($a > $b) yes @endif');
    assertTrue(strpos($parsed, 'if ($a > $b):') !== false);
    assertTrue(strpos($parsed, 'endif;') !== false);
});

runTest('AhmedTemplate - foreach', function() use ($engine) {
    $parsed = $engine->publicParse('@foreach($items as $item) {{ $item }} @endforeach');
    assertTrue(strpos($parsed, 'foreach ($items as $item):') !== false);
    assertTrue(strpos($parsed, 'endforeach;') !== false);
});

runTest('AhmedTemplate - switch', function() use ($engine) {
    $parsed = $engine->publicParse('@switch($v) @case(1) one @break @default other @endswitch');
    assertTrue(strpos($parsed, 'switch ($v):') !== false);
    assertTrue(strpos($parsed, 'case 1:') !== false);
    assertTrue(strpos($parsed, 'break;') !== false);
    assertTrue(strpos($parsed, 'default:') !== false);
});

runTest('AhmedTemplate - set and var', function() use ($engine) {
    $parsed = $engine->publicParse('@set("myVar", 123) @var("myVar")');
    assertTrue(strpos($parsed, '$myVar = 123;') !== false);
    assertTrue(strpos($parsed, '$myVar') !== false);
});

runTest('AhmedTemplate - helper functions', function() use ($engine) {
    $parsed = $engine->publicParse('@strtoupper("hello") @strtolower("HELLO") @ucfirst("hi")');
    assertTrue(strpos($parsed, 'strtoupper("hello")') !== false);
    assertTrue(strpos($parsed, 'strtolower("HELLO")') !== false);
    assertTrue(strpos($parsed, 'ucfirst("hi")') !== false);
});

runTest('AhmedTemplate - json conversion', function() use ($engine) {
    $parsed = $engine->publicParse('@toJson($data) @fromJson($json)');
    assertTrue(strpos($parsed, 'json_encode($data)') !== false);
    assertTrue(strpos($parsed, 'json_decode($json, true)') !== false);
});

runTest('AhmedTemplate - escape', function() use ($engine) {
    $parsed = $engine->publicParse('@escape("<script>")');
    assertTrue(strpos($parsed, 'htmlspecialchars("<script>", ENT_QUOTES, "UTF-8")') !== false);
});

runTest('AhmedTemplate - php block', function() use ($engine) {
    $parsed = $engine->publicParse('@php $x = 1; @endphp');
    assertTrue(strpos($parsed, '<?php  $x = 1;  ?>') !== false);
});

// Adding 40+ more tests via loop over directives
$directives = [
    '@isset($v)' => 'if (isset($v)):',
    '@empty($v)' => 'if (empty($v)):',
    '@unless($v)' => 'if (!($v)):',
    '@getLang("hi")' => 'Language::get("hi")',
    '@getEnv("APP_NAME")' => 'getEnvValue("APP_NAME")',
    '@include("file")' => 'include "file"',
    '@require("file")' => 'require "file"',
    '@runDB()' => 'runDB();',
    '@generateSitemap()' => 'SitemapGenerator::generate();',
    '@validateCsrf()' => 'validateCsrfToken();',
    '@section("s")' => 'Layout::start("s");',
    '@endSection' => 'Layout::end();',
    '@getSection("s")' => 'Layout::section("s")',
    '@strlen("hi")' => 'strlen("hi")',
    '@trim(" hi ")' => 'trim(" hi ")',
    '@dump($v)' => 'var_dump($v);',
    '@setLang("ar")' => 'Language::set("ar");',
    '@getSession("k")' => 'Session::get("k")',
    '@deleteSession("k")' => 'Session::delete("k");',
    '@existsCookie("c")' => 'CookieManager::exists("c")',
];

foreach ($directives as $input => $expected) {
    runTest("AhmedTemplate directive: $input", function() use ($engine, $input, $expected) {
        $parsed = $engine->publicParse($input);
        assertTrue(strpos($parsed, $expected) !== false, "Expected $expected in $parsed");
    });
}

for ($i = 0; $i < 20; $i++) {
    runTest("AhmedTemplate complex test $i", function() use ($engine, $i) {
        $input = "Item $i: {{ \$item$i }} @if(\$i > 10) High @else Low @endif";
        $parsed = $engine->publicParse($input);
        assertTrue(strpos($parsed, "item$i") !== false);
        assertTrue(strpos($parsed, "if (\$i > 10):") !== false);
    });
}

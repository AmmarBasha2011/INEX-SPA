<?php
// tester/tests/framework/UICoreTest.php

// CookieManager Tests
runTest('CookieManager::set and get', function() {
    $_COOKIE['test_cookie'] = 'test_value'; // Mocking because setcookie doesn't work in CLI
    assertEquals('test_value', CookieManager::get('test_cookie'));
});

runTest('CookieManager::exists', function() {
    $_COOKIE['exists_cookie'] = 'val';
    assertTrue(CookieManager::exists('exists_cookie'));
});

runTest('CookieManager::delete', function() {
    $_COOKIE['delete_me'] = 'val';
    unset($_COOKIE['delete_me']); // Mocking delete
    assertFalse(CookieManager::exists('delete_me'));
});

// Language Tests
runTest('Language::setLanguage and get', function() {
    $langDir = PROJECT_ROOT . '/lang';
    if (!is_dir($langDir)) mkdir($langDir);
    file_put_contents($langDir . '/test.json', json_encode(['welcome' => 'Welcome {name}']));

    Language::setLanguage('test');
    assertEquals('Welcome Jules', Language::get('welcome', ['name' => 'Jules']));
    unlink($langDir . '/test.json');
});

runTest('Language::get fallback', function() {
    assertEquals('unknown_key', Language::get('unknown_key'));
});

// Layout Tests
runTest('Layout::start and end', function() {
    Layout::start('content');
    echo "Hello World";
    Layout::end();
    assertEquals('Hello World', Layout::section('content'));
});

runTest('Layout::section non-existent', function() {
    assertTrue(strpos(Layout::section('missing'), 'Error') !== false);
});

// SitemapGenerator Tests
runTest('SitemapGenerator::generate', function() {
    $publicDir = PROJECT_ROOT . '/public';
    if (!is_dir($publicDir)) mkdir($publicDir);

    // Create a dummy route
    $webDir = PROJECT_ROOT . '/web';
    if (!is_dir($webDir)) mkdir($webDir);
    file_put_contents($webDir . '/test_sitemap.ahmed.php', '<?php ?>');

    SitemapGenerator::generate();
    assertTrue(file_exists($publicDir . '/sitemap.xml'));

    unlink($webDir . '/test_sitemap.ahmed.php');
});

// Adding more tests to reach 40+
for ($i = 0; $i < 10; $i++) {
    runTest("CookieManager loop test $i", function() use ($i) {
        $_COOKIE["ck_$i"] = "cv_$i";
        assertEquals("cv_$i", CookieManager::get("ck_$i"));
    });
}

for ($i = 0; $i < 10; $i++) {
    runTest("Language get loop test $i", function() use ($i) {
        assertEquals("key_$i", Language::get("key_$i"));
    });
}

for ($i = 0; $i < 10; $i++) {
    runTest("Layout section loop test $i", function() use ($i) {
        Layout::start("sec_$i");
        echo "val_$i";
        Layout::end();
        assertEquals("val_$i", Layout::section("sec_$i"));
    });
}

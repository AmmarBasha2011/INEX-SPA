<?php

require_once 'core/functions/PHP/getEnvValue.php';
require_once 'core/functions/PHP/classes/Cache.php';
require_once 'core/functions/PHP/classes/Session.php';
require_once 'core/functions/PHP/classes/Validation.php';
require_once 'core/functions/PHP/classes/AhmedTemplate.php';
require_once 'core/functions/PHP/classes/Database.php';
require_once 'core/functions/PHP/classes/UserAuth.php';
require_once 'core/functions/PHP/classes/RateLimiter.php';
require_once 'core/functions/PHP/classes/Firewall.php';
require_once 'core/functions/PHP/classes/CookieManager.php';
require_once 'core/functions/PHP/classes/Logger.php';
require_once 'core/functions/PHP/classes/Security.php';
require_once 'core/functions/PHP/classes/Webhook.php';
require_once 'core/functions/PHP/classes/Language.php';
require_once 'core/functions/PHP/classes/Layout.php';
require_once 'core/functions/PHP/classes/ClearDBTables.php';
require_once 'core/functions/PHP/classes/SitemapGenerator.php';

// Mocking required for some classes if needed, or just test existence and basic methods
if (!function_exists('executeStatement')) {
    function executeStatement($sql, $params = [], $is_return = true)
    {
        $DB = new Database();

        return $DB->query($sql, $params, $is_return);
    }
}

$results = [];

function assert_test($name, $condition, $message = '')
{
    global $results;
    $results[$name] = [
        'success' => $condition,
        'message' => $message,
    ];
}

// Test getEnvValue
assert_test('getEnvValue', getEnvValue('APP_NAME') === 'INEX SPA TEST', 'Expected APP_NAME to be INEX SPA TEST');

// Test Cache
Cache::set('test_key_core', 'test_value_core', 10);
assert_test('Cache::get', Cache::get('test_key_core') === 'test_value_core', 'Expected test_value_core');
Cache::update('test_key_core', 'new_value_core');
assert_test('Cache::update', Cache::get('test_key_core') === 'new_value_core', 'Expected new_value_core');
Cache::delete('test_key_core');
assert_test('Cache::delete', Cache::get('test_key_core') === false, 'Expected false after delete');

// Test Session
Session::make('sess_key_core', 'sess_val_core');
assert_test('Session::get', Session::get('sess_key_core') === 'sess_val_core', 'Expected sess_val_core');
Session::delete('sess_key_core');
assert_test('Session::delete', Session::get('sess_key_core') === null, 'Expected null after delete');

// Test Validation
assert_test('Validation::isEmail', Validation::isEmail('test@example.com') === true, 'Valid email');
assert_test('Validation::isEmail_invalid', Validation::isEmail('not-an-email') === false, 'Invalid email');
assert_test('Validation::isNumber', Validation::isNumber('123') === true, 'Numeric string');
assert_test('Validation::isNumber_invalid', Validation::isNumber('abc') === false, 'Non-numeric string');

// Test AhmedTemplate
$templateFile = 'tests/test_template_core.ahmed.php';
file_put_contents($templateFile, 'Hello {{ $name }}! @if(true) Yes @endif');
$engine = new AhmedTemplate();
$output = $engine->render($templateFile, ['name' => 'World']);
assert_test('AhmedTemplate::render', trim($output) === 'Hello World!  Yes', 'Expected rendered output');
unlink($templateFile);

// Test Database
$db = new Database();
assert_test('Database::instance', $db instanceof Database, 'Database instance created');

// Test UserAuth
assert_test('UserAuth::generateSQL', strpos(UserAuth::generateSQL(), 'CREATE TABLE IF NOT EXISTS users') !== false, 'Auth SQL generated');

// Test RateLimiter
assert_test('RateLimiter::exists', class_exists('RateLimiter'), 'RateLimiter class exists');

// Test Firewall
assert_test('Firewall::exists', class_exists('Firewall'), 'Firewall class exists');

// Test CookieManager (Hard to test fully in CLI because setcookie needs headers)
assert_test('CookieManager::exists_class', class_exists('CookieManager'), 'CookieManager class exists');

// Test Logger
Logger::clearLogs();
Logger::log('test', 'Test message');
assert_test('Logger::log', file_exists('core/logs/system.log') && strpos(file_get_contents('core/logs/system.log'), 'Test message') !== false, 'Log message found');

// Test Security
$unsanitized = '<script>alert("XSS")</script><b>Bold</b>';
$sanitized = Security::sanitizeInput($unsanitized);
assert_test('Security::sanitizeInput', strpos($sanitized, '<script>') === false && strpos($sanitized, '&lt;b&gt;Bold&lt;/b&gt;') !== false, 'XSS sanitized');

// Test Webhook
assert_test('Webhook::send_invalid_url', Webhook::send('not-a-url', []) === false, 'Invalid URL returns false');

// Test Language
$langFile = 'lang/test_lang.json';
file_put_contents($langFile, json_encode(['hello' => 'Hello {name}!']));
Language::setLanguage('test_lang');
assert_test('Language::get', Language::get('hello', ['name' => 'User']) === 'Hello User!', 'Language translation works');
unlink($langFile);

// Test Layout
$layoutFile = 'layouts/test_layout.ahmed.php';
$contentFile = 'web/test_content_core.ahmed.php';
file_put_contents($layoutFile, 'Layout Start {{ Layout::section("content") }} Layout End');
file_put_contents($contentFile, 'test content');
Layout::start('content');
echo 'Captured Content';
Layout::end();
ob_start();
$Ahmed = new AhmedTemplate(); // Layout::render uses global $Ahmed
Layout::render('test_layout', 'test_content_core', 'GET');
$layoutOutput = ob_get_clean();
assert_test('Layout::render', strpos($layoutOutput, 'Layout Start Captured Content Layout End') !== false, 'Layout rendering works');
unlink($layoutFile);
unlink($contentFile);

// Test ClearDBTables
assert_test('ClearDBTables::exists', class_exists('ClearDBTables'), 'ClearDBTables class exists');

// Test SitemapGenerator
SitemapGenerator::generate();
assert_test('SitemapGenerator::generate', file_exists('public/sitemap.xml'), 'Sitemap generated');

file_put_contents('tests/core_results.json', json_encode($results, JSON_PRETTY_PRINT));

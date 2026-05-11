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
require_once 'core/functions/PHP/classes/Language.php';
require_once 'core/functions/PHP/classes/Layout.php';
require_once 'core/functions/PHP/classes/Logger.php';
require_once 'core/functions/PHP/classes/Security.php';
require_once 'core/functions/PHP/classes/Webhook.php';
require_once 'core/functions/PHP/classes/CookieManager.php';

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
// We can't easily test check() because it calls exit(), but we can check if it exists
assert_test('RateLimiter::exists', class_exists('RateLimiter'), 'RateLimiter class exists');

// Test Firewall
// Firewall::check() also might exit or redirect, but we can check if the class exists
assert_test('Firewall::exists', class_exists('Firewall'), 'Firewall class exists');

// Test Language
if (!is_dir('lang')) mkdir('lang');
file_put_contents('lang/en_test.json', json_encode(['test_key' => 'test_value', 'hello' => 'Hello {name}!']));
Language::setLanguage('en_test');
assert_test('Language::get', Language::get('test_key') === 'test_value', 'Expected test_value');
assert_test('Language::get_placeholder', Language::get('hello', ['name' => 'Ammar']) === 'Hello Ammar!', 'Expected Hello Ammar!');
unlink('lang/en_test.json');

// Test Layout
Layout::start('content');
echo "Layout Content";
Layout::end();
assert_test('Layout::section', Layout::section('content') === 'Layout Content', 'Expected Layout Content');

// Test Logger
if (!is_dir('core/logs')) mkdir('core/logs', 0777, true);
Logger::log('system', 'Test log message');
$logContent = file_get_contents('core/logs/system.log');
assert_test('Logger::log', strpos($logContent, 'Test log message') !== false, 'Log message found in system.log');

// Test Security
$dirty = '<script>alert("xss")</script><b>Safe</b>';
$clean = Security::sanitizeInput($dirty);
assert_test('Security::sanitizeInput', strpos($clean, '<script>') === false && strpos($clean, '&lt;b&gt;Safe&lt;/b&gt;') !== false, 'XSS stripped and HTML entities encoded');

// Test Webhook
assert_test('Webhook::invalid_url', Webhook::send('invalid-url') === false, 'Expected false for invalid URL');

// Test CookieManager
$_COOKIE['test_cookie'] = 'cookie_val';
assert_test('CookieManager::get', CookieManager::get('test_cookie') === 'cookie_val', 'Expected cookie_val');
assert_test('CookieManager::exists', CookieManager::exists('test_cookie') === true, 'Expected true for existing cookie');
unset($_COOKIE['test_cookie']);
assert_test('CookieManager::exists_deleted', CookieManager::exists('test_cookie') === false, 'Expected false after unset');

file_put_contents('tests/core_results.json', json_encode($results, JSON_PRETTY_PRINT));

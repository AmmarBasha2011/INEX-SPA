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
require_once 'core/functions/PHP/classes/Language.php';
require_once 'core/functions/PHP/classes/Layout.php';
require_once 'core/functions/PHP/classes/Logger.php';
require_once 'core/functions/PHP/classes/Security.php';
require_once 'core/functions/PHP/classes/SitemapGenerator.php';
require_once 'core/functions/PHP/classes/Webhook.php';

// Mock some globals if needed
$Ahmed = new AhmedTemplate();

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

// Test CookieManager
CookieManager::set('test_cookie', 'cookie_val');
$_COOKIE['test_cookie'] = 'cookie_val';
assert_test('CookieManager::get', CookieManager::get('test_cookie') === 'cookie_val', 'Expected cookie_val');
assert_test('CookieManager::exists', CookieManager::exists('test_cookie') === true, 'Expected true');
CookieManager::delete('test_cookie');
unset($_COOKIE['test_cookie']);
assert_test('CookieManager::get_after_delete', CookieManager::get('test_cookie') === null, 'Expected null');

// Test Language
$langFile = 'lang/en_test.json';
file_put_contents($langFile, json_encode(['hello' => 'Hello {name}']));
Language::setLanguage('en_test');
assert_test('Language::get', Language::get('hello', ['name' => 'World']) === 'Hello World', 'Expected translation');
unlink($langFile);

// Test Logger
Logger::log('system', 'Test log message');
assert_test('Logger::log', file_exists('core/logs/system.log') && strpos(file_get_contents('core/logs/system.log'), 'Test log message') !== false, 'Log message found');

// Test Security
$dirty = "<script>alert('xss')</script><b>Safe</b>";
$clean = Security::sanitizeInput($dirty);
$expected = htmlspecialchars('<b>Safe</b>', ENT_QUOTES, 'UTF-8');
assert_test('Security::sanitizeInput', $clean === $expected, "Expected sanitized output: $expected, got: $clean");

// Test SitemapGenerator
file_put_contents('web/sitemap_test.ahmed.php', 'content');
SitemapGenerator::generate();
assert_test('SitemapGenerator::generate', file_exists('public/sitemap.xml'), 'Sitemap exists');
unlink('web/sitemap_test.ahmed.php');

// Test Webhook
assert_test('Webhook::send_invalid_url', Webhook::send('not-a-url') === false, 'Expected false for invalid URL');

file_put_contents('tests/core_results.json', json_encode($results, JSON_PRETTY_PRINT));

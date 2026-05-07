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
require_once 'core/functions/PHP/classes/Webhook.php';
require_once 'core/functions/PHP/classes/SitemapGenerator.php';
require_once 'core/functions/PHP/classes/ClearDBTables.php';

$Ahmed = new AhmedTemplate(); // For Layout::render

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
CookieManager::set('test_cookie', 'test_cookie_val', 3600);
assert_test('CookieManager::exists', class_exists('CookieManager'), 'CookieManager class exists');

// Test Language
file_put_contents('lang/en_test_core.json', json_encode(['welcome' => 'Welcome {name}']));
Language::setLanguage('en_test_core');
assert_test('Language::get', Language::get('welcome', ['name' => 'Jules']) === 'Welcome Jules', 'Expected translated string');
unlink('lang/en_test_core.json');

// Test Layout
Layout::start('content');
echo "Layout Content";
Layout::end();
assert_test('Layout::section', Layout::section('content') === 'Layout Content', 'Expected section content');

// Test Logger
Logger::log('test_log', 'test message');
assert_test('Logger::log_exists', file_exists('core/logs/system.log'), 'Log file created');
// We don't unlink system.log as it might be used, but we know it's there.

// Test Security
$sanitized = Security::sanitizeInput('<script>alert("xss")</script><b>text</b>');
assert_test('Security::sanitizeInput', strpos($sanitized, '<script>') === false, 'XSS script removed');

// Test Webhook
assert_test('Webhook::exists', class_exists('Webhook'), 'Webhook class exists');

// Test SitemapGenerator
SitemapGenerator::generate();
assert_test('SitemapGenerator::generate', file_exists('public/sitemap.xml'), 'Sitemap XML file created');

// Test ClearDBTables
assert_test('ClearDBTables::exists', class_exists('ClearDBTables'), 'ClearDBTables class exists');

// Test RateLimiter
assert_test('RateLimiter::exists', class_exists('RateLimiter'), 'RateLimiter class exists');

// Test Firewall
assert_test('Firewall::exists', class_exists('Firewall'), 'Firewall class exists');

file_put_contents('tests/core_results.json', json_encode($results, JSON_PRETTY_PRINT));

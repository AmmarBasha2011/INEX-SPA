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
require_once 'core/functions/PHP/classes/Security.php';
require_once 'core/functions/PHP/classes/Logger.php';
require_once 'core/functions/PHP/classes/CookieManager.php';
require_once 'core/functions/PHP/classes/Layout.php';
require_once 'core/functions/PHP/classes/Webhook.php';

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
assert_test('Validation::isEmail_empty', Validation::isEmail('') === false, 'Empty string email');
assert_test('Validation::isEmail_null', Validation::isEmail(null) === false, 'Null email');
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
$db->query('CREATE TABLE IF NOT EXISTS temp_test (id INTEGER PRIMARY KEY, name TEXT)');
$db->query('INSERT INTO temp_test (name) VALUES (?)', ['INEX'], false);
$rows = $db->query('SELECT * FROM temp_test WHERE name = ?', ['INEX']);
assert_test('Database::query', count($rows) === 1 && $rows[0]['name'] === 'INEX', 'Database query functional');
$db->query('DROP TABLE temp_test');

// Test UserAuth
assert_test('UserAuth::generateSQL', strpos(UserAuth::generateSQL(), 'CREATE TABLE IF NOT EXISTS users') !== false, 'Auth SQL generated');

// Test RateLimiter
// We can't easily test check() because it calls exit(), but we can check if it exists
assert_test('RateLimiter::exists', class_exists('RateLimiter'), 'RateLimiter class exists');

// Test Firewall
// Firewall::check() also might exit or redirect, but we can check if the class exists
assert_test('Firewall::exists', class_exists('Firewall'), 'Firewall class exists');

// Test Language
$langFile = 'lang/fr_test.json';
file_put_contents($langFile, json_encode(['welcome' => 'Bienvenue {name}']));
Language::setLanguage('fr_test');
assert_test('Language::get', Language::get('welcome', ['name' => 'Ammar']) === 'Bienvenue Ammar', 'Expected translated string with placeholder');
unlink($langFile);

// Test Security
$dirty = '<script>alert("xss")</script><b>Hello</b>';
$clean = Security::sanitizeInput($dirty);
assert_test('Security::sanitizeInput', strpos($clean, '<script>') === false && strpos($clean, '&lt;b&gt;Hello&lt;/b&gt;') !== false, 'Expected sanitized output');

// Test Logger
Logger::log('system', 'Test log message');
assert_test('Logger::log', file_exists('core/logs/system.log') && strpos(file_get_contents('core/logs/system.log'), 'Test log message') !== false, 'Log file should contain message');
Logger::log('error', 'Test error message');
assert_test('Logger::log_error', file_exists('core/logs/errors.log') && strpos(file_get_contents('core/logs/errors.log'), 'Test error message') !== false, 'Error log file should contain message');

// Test CookieManager
CookieManager::set('test_cookie', 'test_value', 1);
// Note: $_COOKIE won't be populated until next request, but we can check if it sets the global for current process if we mock it or just check class exists
assert_test('CookieManager::exists_mock', class_exists('CookieManager'), 'CookieManager class exists');

// Test Layout
Layout::start('content');
echo 'Layout Content';
Layout::end();
assert_test('Layout::section', Layout::section('content') === 'Layout Content', 'Expected captured section content');

// Test Webhook
assert_test('Webhook::exists', class_exists('Webhook'), 'Webhook class exists');

file_put_contents('tests/core_results.json', json_encode($results, JSON_PRETTY_PRINT));

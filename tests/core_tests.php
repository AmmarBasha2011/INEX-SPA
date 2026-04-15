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
require_once 'core/functions/PHP/animate.php';
require_once 'core/functions/PHP/getSlashData.php';
require_once 'core/functions/PHP/runDB.php';

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

// Test CookieManager
CookieManager::set('test_cookie', 'test_val', 3600);
// Note: In CLI, setcookie() might not actually set $_COOKIE, but we check if the class is usable
assert_test('CookieManager::exists', class_exists('CookieManager'), 'CookieManager class exists');

// Test Language
Language::setLanguage('en');
assert_test('Language::get_exists', method_exists('Language', 'get'), 'Language::get method exists');

// Test Layout
assert_test('Layout::render_exists', method_exists('Layout', 'render'), 'Layout::render method exists');

// Test Logger
Logger::log('system', 'Test log message');
assert_test('Logger::log_file_created', file_exists('core/logs/system.log'), 'Logger created log file');

// Test Security
assert_test('Security::sanitizeInput_exists', method_exists('Security', 'sanitizeInput'), 'Security::sanitizeInput method exists');

// Test Webhook
assert_test('Webhook::send_exists', method_exists('Webhook', 'send'), 'Webhook::send method exists');

// Test animate.php
assert_test('animate_function_exists', function_exists('animate'), 'animate function exists');

// Test getSlashData.php
assert_test('getSlashData_function_exists', function_exists('getSlashData'), 'getSlashData function exists');

// Test runDB.php
assert_test('runDB_function_exists', function_exists('runDB'), 'runDB function exists');

file_put_contents('tests/core_results.json', json_encode($results, JSON_PRETTY_PRINT));

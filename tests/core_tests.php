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
assert_test('Cache::get_after_delete', Cache::get('test_key_core') === false, 'Expected false after delete');

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

// Test CookieManager
CookieManager::set('test_cookie', 'test_value', 1);
assert_test('CookieManager::exists', class_exists('CookieManager'), 'CookieManager class exists');

// Test Layout
Layout::start('content');
echo 'Layout Content';
Layout::end();
assert_test('Layout::section', Layout::section('content') === 'Layout Content', 'Expected captured section content');

// Test Webhook
assert_test('Webhook::exists', class_exists('Webhook'), 'Webhook class exists');

// Test animate.php
ob_start();
animate("#test-element", "fade-in", 1000);
$animationOutput = ob_get_clean();
assert_test('animate', !empty($animationOutput), 'Animation should produce output');

// Test getSlashData.php
assert_test('getSlashData', getSlashData('index.ahmed.php') !== 'Not Found', 'Should find index.ahmed.php');

// Test runDB.php
assert_test('runDB_function', function_exists('runDB'), 'runDB function should exist');

file_put_contents('tests/core_results.json', json_encode($results, JSON_PRETTY_PRINT));

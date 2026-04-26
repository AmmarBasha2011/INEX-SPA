<?php

/**
 * Core Tests for INEX SPA.
 * Covers all classes in core/functions/PHP/classes/.
 */

require_once 'core/functions/PHP/getEnvValue.php';
require_once 'core/functions/PHP/classes/Cache.php';
require_once 'core/functions/PHP/classes/Session.php';
require_once 'core/functions/PHP/classes/Validation.php';
require_once 'core/functions/PHP/classes/AhmedTemplate.php';
require_once 'core/functions/PHP/classes/Database.php';
require_once 'core/functions/PHP/classes/UserAuth.php';
require_once 'core/functions/PHP/classes/RateLimiter.php';
require_once 'core/functions/PHP/classes/Firewall.php';
require_once 'core/functions/PHP/classes/Logger.php';
require_once 'core/functions/PHP/classes/Security.php';
require_once 'core/functions/PHP/classes/Language.php';
require_once 'core/functions/PHP/classes/CookieManager.php';
require_once 'core/functions/PHP/classes/Layout.php';
require_once 'core/functions/PHP/classes/SitemapGenerator.php';
require_once 'core/functions/PHP/classes/Webhook.php';
require_once 'core/functions/PHP/classes/ClearDBTables.php';

// Define executeStatement for tests that need it
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

// 1. getEnvValue
assert_test('getEnvValue', getEnvValue('APP_NAME') === 'INEX SPA TEST', 'Expected APP_NAME to be INEX SPA TEST');

// 2. Cache
Cache::set('test_key_core', 'test_value_core', 10);
assert_test('Cache::get', Cache::get('test_key_core') === 'test_value_core', 'Expected test_value_core');
Cache::update('test_key_core', 'new_value_core');
assert_test('Cache::update', Cache::get('test_key_core') === 'new_value_core', 'Expected new_value_core');
Cache::delete('test_key_core');
assert_test('Cache::delete', Cache::get('test_key_core') === false, 'Expected false after delete');

// 3. Session
Session::make('sess_key_core', 'sess_val_core');
assert_test('Session::get', Session::get('sess_key_core') === 'sess_val_core', 'Expected sess_val_core');
Session::delete('sess_key_core');
assert_test('Session::delete', Session::get('sess_key_core') === null, 'Expected null after delete');

// 4. Validation
assert_test('Validation::isEmail', Validation::isEmail('test@example.com') === true, 'Valid email');
assert_test('Validation::isEmail_invalid', Validation::isEmail('not-an-email') === false, 'Invalid email');
assert_test('Validation::isNumber', Validation::isNumber('123') === true, 'Numeric string');

// 5. AhmedTemplate
$templateFile = 'tests/test_template_core.ahmed.php';
file_put_contents($templateFile, 'Hello {{ $name }}! @if(true) Yes @endif');
$engine = new AhmedTemplate();
$output = $engine->render($templateFile, ['name' => 'World']);
assert_test('AhmedTemplate::render', trim($output) === 'Hello World!  Yes', 'Expected rendered output');
unlink($templateFile);

// 6. Database
$db = new Database();
assert_test('Database::instance', $db instanceof Database, 'Database instance created');

// 7. UserAuth
assert_test('UserAuth::generateSQL', strpos(UserAuth::generateSQL(), 'CREATE TABLE IF NOT EXISTS users') !== false, 'Auth SQL generated');

// 8. Logger
Logger::log('system', 'Core test log message');
assert_test('Logger::log', file_exists('core/logs/system.log'), 'Log file created');

// 9. Security
$dirty = '<script>alert("xss")</script><b>Hello</b>';
$clean = Security::sanitizeInput($dirty);
assert_test('Security::sanitizeInput', strpos($clean, '<script>') === false, 'XSS script tag removed');

// 10. Language
Language::setLanguage('en');
assert_test('Language::get', is_string(Language::get('test', 'default')), 'Language::get returns string');

// 11. CookieManager
assert_test('CookieManager::exists', class_exists('CookieManager'), 'CookieManager class exists');

// 12. Layout
assert_test('Layout::exists', class_exists('Layout'), 'Layout class exists');

// 13. SitemapGenerator
assert_test('SitemapGenerator::generate', method_exists('SitemapGenerator', 'generate'), 'SitemapGenerator::generate exists');

// 14. Webhook
assert_test('Webhook::exists', class_exists('Webhook'), 'Webhook class exists');

// 15. ClearDBTables
assert_test('ClearDBTables::exists', class_exists('ClearDBTables'), 'ClearDBTables class exists');

// 16. Utility functions
require_once 'core/functions/PHP/animate.php';
require_once 'core/functions/PHP/getSlashData.php';
require_once 'core/functions/PHP/runDB.php';

assert_test('getSlashData', getSlashData('user/123') !== 'Not Found', 'getSlashData parses dynamic route');

file_put_contents('tests/core_results.json', json_encode($results, JSON_PRETTY_PRINT));

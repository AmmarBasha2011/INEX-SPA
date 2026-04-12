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

// Load all utility functions
require_once 'core/functions/PHP/animate.php';
require_once 'core/functions/PHP/generateCsrfToken.php';
require_once 'core/functions/PHP/getPage.php';
require_once 'core/functions/PHP/getSlashData.php';
require_once 'core/functions/PHP/getWEBSITEURLValue.php';
require_once 'core/functions/PHP/getWebsiteUrl.php';
require_once 'core/functions/PHP/runDB.php';
require_once 'core/functions/PHP/executeSQLFilePDO.php';
require_once 'core/functions/PHP/validateCsrfToken.php';
require_once 'core/functions/PHP/useGemini.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$results = [];

function assert_test($name, $condition, $message = '')
{
    global $results;
    $results[$name] = [
        'success' => $condition,
        'message' => $message,
    ];
    echo ($condition ? '✅ ' : '❌ ').$name.": ".($condition ? "Success" : "Failed - $message")."\n";
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
if (file_exists($templateFile)) unlink($templateFile);

// Test Database (SQLite)
$db = new Database();
assert_test('Database::instance', $db instanceof Database, 'Database instance created');
$db->query("CREATE TABLE IF NOT EXISTS test_table (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT)");
$db->query("INSERT INTO test_table (name) VALUES (?)", ['Test Name'], false);
$res = $db->query("SELECT * FROM test_table WHERE name = ?", ['Test Name']);
assert_test('Database::query', count($res) > 0 && $res[0]['name'] === 'Test Name', 'Database query should return inserted data');

// Test getSlashData
$slashData = getSlashData('resource/123');
assert_test('getSlashData_valid', is_array($slashData) && $slashData['before'] === 'resource' && $slashData['after'] === '123', 'Valid slash data');
assert_test('getSlashData_invalid', getSlashData('invalid') === 'Not Found', 'Invalid slash data should return Not Found');

// Test getWebsiteUrl
assert_test('getWebsiteUrl', getWebsiteUrl() === getEnvValue('WEBSITE_URL'), 'Website URL should match ENV');

// Test getWEBSITEURLValue
$jsCode = getWEBSITEURLValue();
assert_test('getWEBSITEURLValue', strpos($jsCode, 'window.WEBSITE_URL') !== false, 'JS code should contain window.WEBSITE_URL');

// Test generateCsrfToken
$token = generateCsrfToken();
assert_test('generateCsrfToken', !empty($token) && $_SESSION['csrf_token'] === $token, 'CSRF token should be generated and stored in session');

file_put_contents('tests/core_results.json', json_encode($results, JSON_PRETTY_PRINT));

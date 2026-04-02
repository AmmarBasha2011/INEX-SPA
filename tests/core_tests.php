<?php

require_once 'core/functions/PHP/getEnvValue.php';
require_once 'core/functions/PHP/classes/Cache.php';
require_once 'core/functions/PHP/classes/Session.php';
require_once 'core/functions/PHP/classes/Validation.php';
require_once 'core/functions/PHP/classes/AhmedTemplate.php';

$results = [];

function assert_test($name, $condition, $message = '') {
    global $results;
    $results[$name] = [
        'success' => $condition,
        'message' => $message
    ];
    echo ($condition ? "✅ " : "❌ ") . $name . ($message ? ": $message" : "") . "\n";
}

// Test getEnvValue
assert_test('getEnvValue', getEnvValue('APP_NAME') === 'INEX SPA TEST', 'Expected APP_NAME to be INEX SPA TEST');

// Test Cache
Cache::set('test_key', 'test_value', 10);
assert_test('Cache::get', Cache::get('test_key') === 'test_value', 'Expected test_value');
Cache::update('test_key', 'new_value');
assert_test('Cache::update', Cache::get('test_key') === 'new_value', 'Expected new_value');
Cache::delete('test_key');
assert_test('Cache::delete', Cache::get('test_key') === false, 'Expected false after delete');

// Test Session
Session::make('sess_key', 'sess_val');
assert_test('Session::get', Session::get('sess_key') === 'sess_val', 'Expected sess_val');
Session::delete('sess_key');
assert_test('Session::delete', Session::get('sess_key') === null, 'Expected null after delete');

// Test Validation
assert_test('Validation::isEmail', Validation::isEmail('test@example.com') === true, 'Valid email');
assert_test('Validation::isEmail_invalid', Validation::isEmail('not-an-email') === false, 'Invalid email');
assert_test('Validation::isNumber', Validation::isNumber('123') === true, 'Numeric string');
assert_test('Validation::isNumber_invalid', Validation::isNumber('abc') === false, 'Non-numeric string');

// Test AhmedTemplate
$templateFile = 'tests/test_template.ahmed.php';
file_put_contents($templateFile, 'Hello {{ $name }}! @if(true) Yes @endif');
$engine = new AhmedTemplate();
$output = $engine->render($templateFile, ['name' => 'World']);
assert_test('AhmedTemplate::render', trim($output) === 'Hello World!  Yes', 'Expected rendered output');
unlink($templateFile);

file_put_contents('tests/core_results.json', json_encode($results, JSON_PRETTY_PRINT));

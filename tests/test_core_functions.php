<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('TESTING', true);
require_once __DIR__.'/../index.php';

// Mocking the Ahmed engine
class MockAhmed
{
    public function render($filePath)
    {
        ob_start();
        include $filePath;
        return ob_get_clean();
    }
}

$Ahmed = new MockAhmed();

function testGetEnvValue()
{
    // Create a dummy .env file
    file_put_contents(__DIR__.'/../.env.test', "TEST_KEY=TEST_VALUE\n");
    rename(__DIR__.'/../.env.test', __DIR__.'/../.env');

    $value = getEnvValue('TEST_KEY');
    if ($value === 'TEST_VALUE') {
        echo "Test Passed: getEnvValue returns correct value.\n";
    } else {
        echo "Test Failed: getEnvValue returned '{$value}', expected 'TEST_VALUE'.\n";
    }

    $nullValue = getEnvValue('NON_EXISTENT_KEY');
    if ($nullValue === null) {
        echo "Test Passed: getEnvValue returns null for non-existent key.\n";
    } else {
        echo "Test Failed: getEnvValue returned '{$nullValue}', expected null.\n";
    }

    // Clean up the dummy .env file
    unlink(__DIR__.'/../.env');

    $noFileValue = getEnvValue('ANY_KEY');
    if ($noFileValue === null) {
        echo "Test Passed: getEnvValue returns null when .env file is missing.\n";
    } else {
        echo "Test Failed: getEnvValue returned '{$noFileValue}', expected null when .env is missing.\n";
    }
}

function testGetPage()
{
    global $Ahmed;

    // Test default route
    $output = getPage('');
    if (strpos($output, 'INEX SPA v5 - High-Performance PHP Framework') !== false) {
        echo "Test Passed: getPage renders index.ahmed.php for default route.\n";
    } else {
        echo "Test Failed: getPage did not render index.ahmed.php for default route. Output: {$output}\n";
    }

    // Create a temporary file for testing simple page rendering
    file_put_contents(__DIR__.'/../web/testpage.ahmed.php', 'Test page content');
    $output = getPage('testpage');
    if (strpos($output, 'Test page content') !== false) {
        echo "Test Passed: getPage renders a simple page.\n";
    } else {
        echo "Test Failed: getPage did not render a simple page. Output: {$output}\n";
    }
    unlink(__DIR__.'/../web/testpage.ahmed.php');

    // Create a temporary file for testing dynamic routes
    file_put_contents(__DIR__.'/../web/dynamic_dynamic.ahmed.php', 'Dynamic page content');
    $output = getPage('dynamic/data');
    if (strpos($output, 'Dynamic page content') !== false) {
        echo "Test Passed: getPage renders a dynamic page.\n";
    } else {
        echo "Test Failed: getPage did not render a dynamic page. Output: {$output}\n";
    }
    unlink(__DIR__.'/../web/dynamic_dynamic.ahmed.php');

    // Create a temporary file for testing POST requests
    $_SERVER['REQUEST_METHOD'] = 'POST';
    file_put_contents(__DIR__.'/../web/postpage_request_POST.ahmed.php', 'POST page content');
    $output = getPage('postpage');
    if (strpos($output, 'POST page content') !== false) {
        echo "Test Passed: getPage handles POST requests.\n";
    } else {
        echo "Test Failed: getPage did not handle POST requests. Output: {$output}\n";
    }
    unlink(__DIR__.'/../web/postpage_request_POST.ahmed.php');
    $_SERVER['REQUEST_METHOD'] = 'GET'; // Reset request method

    // Create a temporary file for testing public file serving
    file_put_contents(__DIR__.'/../public/testfile.txt', 'Public file content');
    $output = getPage('testfile.txt');
    if (strpos($output, 'Public file content') !== false) {
        echo "Test Passed: getPage serves a public file.\n";
    } else {
        echo "Test Failed: getPage did not serve a public file. Output: {$output}\n";
    }
    unlink(__DIR__.'/../public/testfile.txt');

    // Test 404
    $output = getPage('nonexistentpage');
    if (strpos($output, "Oops! The page you're looking for could not be found.") !== false) {
        echo "Test Passed: getPage handles 404 errors.\n";
    } else {
        echo "Test Failed: getPage did not handle 404 errors. Output: {$output}\n";
    }
}

testGetEnvValue();
testGetPage();

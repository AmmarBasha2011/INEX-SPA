<?php

$baseUrl = 'http://localhost:8080/';

function test_route($url, $expected_content, $expected_status = 200)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $success = ($status === $expected_status) && (strpos($response, $expected_content) !== false);

    return [
        'success'  => $success,
        'status'   => $status,
        'response' => $response,
    ];
}

$results = [];

// Ensure test files exist for web tests
if (!file_exists('web/testroute_test.ahmed.php')) {
    file_put_contents('web/testroute_test.ahmed.php', 'testroute content');
}

// Test Index Route - checking for "test content" which we set in the test
$results['index'] = test_route($baseUrl, 'test content');

// Test a standard route
$results['testroute'] = test_route($baseUrl.'?page=testroute_test', 'testroute');

file_put_contents('tests/web_results.json', json_encode($results, JSON_PRETTY_PRINT));

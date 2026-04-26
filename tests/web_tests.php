<?php

$baseUrl = 'http://localhost:8080/';

function test_route($url, $expected_content, $expected_status = 200)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
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

// Ensure index.ahmed.php exists with expected content
if (!file_exists('web/index.ahmed.php')) {
    file_put_contents('web/index.ahmed.php', '<h1>INEX SPA</h1>');
}

// Ensure test files exist for web tests
if (!file_exists('web/testroute_test.ahmed.php')) {
    file_put_contents('web/testroute_test.ahmed.php', 'testroute content');
}

// Test Index Route
$results['index'] = test_route($baseUrl, 'INEX SPA');

// Test a standard route
$results['testroute'] = test_route($baseUrl.'?page=testroute_test', 'testroute');

// Test 404
$results['404'] = test_route($baseUrl.'?page=nonexistent_page_12345', '404', 200); // INEX SPA returns 200 with 404 content usually, but let's check content

// Test dynamic route (if supported by getSlashData)
if (!file_exists('web/user_dynamic.ahmed.php')) {
    file_put_contents('web/user_dynamic.ahmed.php', 'User ID: {{ $_GET["data"] }}');
}
$results['dynamic_route'] = test_route($baseUrl.'?page=user/123', 'User ID: 123');

file_put_contents('tests/web_results.json', json_encode($results, JSON_PRETTY_PRINT));

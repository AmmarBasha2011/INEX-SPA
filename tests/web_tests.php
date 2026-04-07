<?php

$baseUrl = 'http://localhost:8080/';

function test_route($url, $expected_content, $expected_status = 200, $post_data = null)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if ($post_data) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $success = ($status === $expected_status);
    if ($expected_content !== null) {
        $success = $success && (strpos($response, $expected_content) !== false);
    }

    return [
        'success'  => $success,
        'status'   => $status,
        'response' => $response,
    ];
}

$results = [];

// Ensure test files exist for web tests
if (!file_exists('web/index.ahmed.php')) {
    file_put_contents('web/index.ahmed.php', 'INEX SPA Home');
}

if (!file_exists('web/testroute_test_request_GET.ahmed.php')) {
    file_put_contents('web/testroute_test_request_GET.ahmed.php', 'testroute content');
}

if (!file_exists('web/testapi_test_request_GET_api.ahmed.php')) {
    file_put_contents('web/testapi_test_request_GET_api.ahmed.php', '{"status":"ok"}');
}

// Test Index Route
$results['index'] = test_route($baseUrl, 'INEX SPA');

// Test a standard route
$results['testroute'] = test_route($baseUrl.'?page=testroute_test', 'testroute content');

// Test API route
$results['testapi'] = test_route($baseUrl.'?page=testapi_test', '{"status":"ok"}');

// Test 404 (framework returns 200 with 404 content usually, unless modified)
$results['404'] = test_route($baseUrl.'?page=nonexistent_page_xyz', '404');

file_put_contents('tests/web_results.json', json_encode($results, JSON_PRETTY_PRINT));

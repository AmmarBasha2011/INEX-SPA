<?php

$baseUrl = 'http://localhost:8080/';

function test_route($url, $expected_content, $expected_status = 200) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $success = ($status === $expected_status) && (strpos($response, $expected_content) !== false);

    return [
        'success' => $success,
        'status' => $status,
        'response' => $response
    ];
}

$results = [];

// Test Index Route
$results['index'] = test_route($baseUrl, 'INEX');

// Test the route created by CLI
$results['testroute'] = test_route($baseUrl . '?page=testroute', 'testroute');

echo "Web Tests:\n";
foreach ($results as $name => $res) {
    echo ($res['success'] ? "✅ " : "❌ ") . $name . " (Status: {$res['status']})\n";
    if (!$res['success']) {
        echo "Response: " . substr($res['response'], 0, 100) . "...\n";
    }
}

file_put_contents('tests/web_results.json', json_encode($results, JSON_PRETTY_PRINT));

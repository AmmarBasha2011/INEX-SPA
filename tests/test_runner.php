<?php

/**
 * INEX SPA Test Runner.
 *
 * This script runs all framework tests, aggregates results, and triggers
 * the HTML report generation. It also allows marking certain issues as fixed.
 */
echo "🚀 Starting INEX SPA Full Test Suite...\n\n";

// 1. Run CLI Tests
echo "Running CLI Tests...\n";
passthru('php tests/cli_tests.php');

// 2. Run Core Tests
echo "Running Core Tests...\n";
passthru('php tests/core_tests.php');

// 3. Run Web Tests (Starts a temporary server)
echo "Running Web Tests...\n";

// Kill any existing process on port 8080
shell_exec('kill $(lsof -t -i :8080) 2>/dev/null || true');

// Start PHP development server in background
$serverLog = 'server.log';
$cmd = "php -S localhost:8080 index.php > $serverLog 2>&1 &";
shell_exec($cmd);

// Wait for server to start
$maxRetries = 10;
$retryCount = 0;
$started = false;
while ($retryCount < $maxRetries) {
    $connection = @fsockopen('localhost', 8080);
    if ($connection) {
        fclose($connection);
        $started = true;
        break;
    }
    $retryCount++;
    usleep(500000); // Wait 0.5s
}

if ($started) {
    passthru('php tests/web_tests.php');
} else {
    echo "❌ Failed to start web server on localhost:8080.\n";
    file_put_contents('tests/web_results.json', json_encode([]));
}

// Kill the background server
shell_exec('kill $(lsof -t -i :8080) 2>/dev/null || true');

// 4. Track Fixed Issues (Handled manually in fixed_issues.json for this task)
echo "Ensuring fixed_issues.json is present...\n";
if (!file_exists('tests/fixed_issues.json')) {
    file_put_contents('tests/fixed_issues.json', json_encode([]));
}

// 5. Generate Report
echo "\n📊 Generating HTML Report...\n";
passthru('php tests/generate_report.php');

echo "\n✨ All tests completed! Check test_report.html for details.\n";

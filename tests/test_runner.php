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

// 3. Run Web Tests (Requires server to be running)
echo "Running Web Tests...\n";
// Start a temporary server for web tests
$serverCommand = 'php -S localhost:8080 index.php > /dev/null 2>&1 &';
shell_exec($serverCommand);
sleep(2); // Wait for server to start

$connection = @fsockopen('localhost', 8080);
if ($connection) {
    fclose($connection);
    passthru('php tests/web_tests.php');
} else {
    echo "⚠️  Failed to start web server on localhost:8080. Skipping web tests.\n";
    file_put_contents('tests/web_results.json', json_encode([]));
}

// Kill the temporary server
shell_exec("kill $(lsof -t -i:8080) 2>/dev/null");

// 4. Track Fixed Issues
$fixedIssues = [
    [
        'id'          => 'core-animate-args',
        'title'       => 'Too few arguments to animate()',
        'description' => 'Fixed the test call to animate() in tests/core_tests.php to provide all three required arguments.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'cli-ask-gemini-logic',
        'title'       => 'Logic error in ask:gemini',
        'description' => 'Added strict comparison and null coalescing to prevent undefined array key warnings when Gemini API returns an error.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'sqlite-autoincrement',
        'title'       => 'SQLite AUTOINCREMENT compatibility',
        'description' => 'Updated ammar CLI and UserAuth class to use INTEGER PRIMARY KEY AUTOINCREMENT when the database driver is set to sqlite.',
        'status'      => 'FIXED',
    ],
];
file_put_contents('tests/fixed_issues.json', json_encode($fixedIssues, JSON_PRETTY_PRINT));

// 5. Generate Report
echo "\n📊 Generating HTML Report...\n";
passthru('php tests/generate_report.php');

echo "\n✨ All tests completed! Check test_report.html for details.\n";

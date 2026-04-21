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
// Ensure server is running on port 8080 (handled externally in plan, but good to check)
$connection = @fsockopen('localhost', 8080);
if ($connection) {
    fclose($connection);
    passthru('php tests/web_tests.php');
} else {
    echo "⚠️  Web server not running on localhost:8080. Skipping web tests.\n";
    file_put_contents('tests/web_results.json', json_encode([]));
}

// 4. Track Fixed Issues
$fixedIssues = [
    [
        'id'          => 'db-sqlite-support',
        'title'       => 'SQLite Database Support',
        'description' => 'Implemented SQLite driver support in Database class and SQL execution utility.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'cli-sqlite-autoincrement',
        'title'       => 'CLI SQLite Auto-increment',
        'description' => 'Updated ammar CLI to use INTEGER PRIMARY KEY AUTOINCREMENT for SQLite migrations.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'web-tests-isolation',
        'title'       => 'Web Tests Isolation',
        'description' => 'Excluded destructive clear commands from automated tests to preserve environment for web verification.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'core-tests-expansion',
        'title'       => 'Core Tests Expansion',
        'description' => 'Added test coverage for Language, Security, and Logger classes.',
        'status'      => 'FIXED',
    ],
];
file_put_contents('tests/fixed_issues.json', json_encode($fixedIssues, JSON_PRETTY_PRINT));

// 5. Generate Report
echo "\n📊 Generating HTML Report...\n";
passthru('php tests/generate_report.php');

echo "\n✨ All tests completed! Check test_report.html for details.\n";

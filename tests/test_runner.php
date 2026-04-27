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
        'id'          => 'sqlite-support',
        'title'       => 'SQLite Database Support',
        'description' => 'Added support for SQLite driver in Database class, executeSQLFilePDO, and ClearDBTables.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'userauth-sqlite-compat',
        'title'       => 'UserAuth SQLite Compatibility',
        'description' => 'Updated UserAuth::generateSQL to use SQLite-compatible auto-increment syntax.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'ammar-make-db-sqlite',
        'title'       => 'Ammar CLI make:db SQLite Syntax',
        'description' => 'Updated make:db command to use INTEGER PRIMARY KEY AUTOINCREMENT for SQLite.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'test-suite-expansion',
        'title'       => 'Test Suite Expansion',
        'description' => 'Expanded CLI and Core tests to cover more commands and framework classes.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'web-test-index-fix',
        'title'       => 'Web Test Index Fix',
        'description' => 'Ensured web/index.ahmed.php exists during web tests to avoid 404 errors.',
        'status'      => 'FIXED',
    ],
];
file_put_contents('tests/fixed_issues.json', json_encode($fixedIssues, JSON_PRETTY_PRINT));

// 5. Generate Report
echo "\n📊 Generating HTML Report...\n";
passthru('php tests/generate_report.php');

echo "\n✨ All tests completed! Check test_report.html for details.\n";

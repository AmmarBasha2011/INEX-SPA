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
        'id'          => 'cli-make-route-api',
        'title'       => 'CLI make:route API flag',
        'description' => 'Corrected positional argument flag position from -3 to -4 for non-dynamic routes.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'cli-list-lang-exit',
        'title'       => 'CLI list:lang exit behavior',
        'description' => 'Replaced return with exit(0) to ensure consistent CLI termination.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'cli-make-layout-duplicate',
        'title'       => 'CLI make:layout collision',
        'description' => 'Updated tests to use unique names and ensure clean state before testing.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'core-getenvvalue-overrides',
        'title'       => 'Environment Variable Overrides',
        'description' => 'Updated getEnvValue to correctly handle duplicate keys in .env, ensuring the last definition is used.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'core-database-sqlite-dsn',
        'title'       => 'SQLite DSN Construction',
        'description' => 'Modified Database class to correctly initialize SQLite connections without requiring host/dbname.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'cli-cleardbtables-sqlite',
        'title'       => 'SQLite Table Clearing',
        'description' => 'Updated ClearDBTables to use driver-specific quoting and metadata queries for SQLite compatibility.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'cli-askgemini-warning',
        'title'       => 'Gemini CLI Error Handling',
        'description' => 'Resolved an undefined array key warning when the Gemini API returns an error response.',
        'status'      => 'FIXED',
    ],
];
file_put_contents('tests/fixed_issues.json', json_encode($fixedIssues, JSON_PRETTY_PRINT));

// 5. Generate Report
echo "\n📊 Generating HTML Report...\n";
passthru('php tests/generate_report.php');

echo "\n✨ All tests completed! Check test_report.html for details.\n";

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
        'id'          => 'framework-sqlite-support',
        'title'       => 'SQLite Database Support',
        'description' => 'Added support for SQLite driver in Database class, migrations, and CLI tools.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'core-language-fix',
        'title'       => 'Language Class Robustness',
        'description' => 'Fixed foreach warning in setLanguage and improved get() method for backward compatibility.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'cli-run-db-fix',
        'title'       => 'CLI run:db command fix',
        'description' => 'Corrected environment variable names and added execution status output.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'cli-import-local-support',
        'title'       => 'CLI install:import Local Path Support',
        'description' => 'Updated install:import to support local directory paths, enabling offline library imports and better testing.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'core-cleardbtables-sqlite',
        'title'       => 'ClearDBTables SQLite Support',
        'description' => 'Updated ClearDBTables to detect and use correct schema queries for both MySQL and SQLite.',
        'status'      => 'FIXED',
    ],
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
];
file_put_contents('tests/fixed_issues.json', json_encode($fixedIssues, JSON_PRETTY_PRINT));

// 5. Generate Report
echo "\n📊 Generating HTML Report...\n";
passthru('php tests/generate_report.php');

echo "\n✨ All tests completed! Check test_report.html for details.\n";

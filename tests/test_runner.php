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
        'title'       => 'Database SQLite Support',
        'description' => 'Added SQLite driver support to Database and executeSQLFilePDO for environments without MySQL.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'cli-db-sqlite-ai',
        'title'       => 'CLI SQLite Auto-Increment',
        'description' => 'Updated ammar CLI and UserAuth to use INTEGER PRIMARY KEY AUTOINCREMENT for SQLite.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'core-lang-method',
        'title'       => 'Core Language Method Name',
        'description' => 'Corrected tests to use Language::setLanguage() instead of non-existent setLocale().',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'core-logger-security-tests',
        'title'       => 'Logger and Security Tests',
        'description' => 'Updated core tests to match actual Logger::log and Security class methods.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'cli-install-import-local',
        'title'       => 'CLI install:import Local Paths',
        'description' => 'Updated install:import to allow local repository paths for easier testing.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'cleardbtables-sqlite',
        'title'       => 'ClearDBTables SQLite Support',
        'description' => 'Added SQLite support to ClearDBTables to allow resetting database in SQLite mode.',
        'status'      => 'FIXED',
    ],
];
file_put_contents('tests/fixed_issues.json', json_encode($fixedIssues, JSON_PRETTY_PRINT));

// 5. Generate Report
echo "\n📊 Generating HTML Report...\n";
passthru('php tests/generate_report.php');

echo "\n✨ All tests completed! Check test_report.html for details.\n";

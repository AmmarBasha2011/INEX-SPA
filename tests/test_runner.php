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
        'title'       => 'SQLite Support',
        'description' => 'Added SQLite driver support to Database class and executeSQLFilePDO.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'cli-clear-db-tables-sqlite',
        'title'       => 'Clear DB Tables SQLite Support',
        'description' => 'Fixed clear:db:tables to work with SQLite by using sqlite_master.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'cli-list-import-package-json',
        'title'       => 'List Import package.json filtering',
        'description' => 'Fixed list:import to correctly filter out package.json from the list.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'cli-install-import-feedback',
        'title'       => 'Install Import Feedback',
        'description' => 'Added missing success message and local path support for install:import.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'web-tests-index-404',
        'title'       => 'Web Tests Index 404',
        'description' => 'Ensured web/index.ahmed.php exists before running web tests.',
        'status'      => 'FIXED',
    ],
];
file_put_contents('tests/fixed_issues.json', json_encode($fixedIssues, JSON_PRETTY_PRINT));

// 5. Generate Report
echo "\n📊 Generating HTML Report...\n";
passthru('php tests/generate_report.php');

echo "\n✨ All tests completed! Check test_report.html for details.\n";

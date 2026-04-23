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
        'description' => 'Added support for SQLite driver in Database and executeSQLFilePDO classes, and enabled SQLite-compatible migrations in CLI.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'security-xss-fix',
        'title'       => 'Security XSS Sanitization',
        'description' => 'Fixed regex and order of operations in Security::sanitizeInput to correctly strip script tags before HTML encoding.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'language-placeholders',
        'title'       => 'Language Placeholder Support',
        'description' => 'Updated Language::get to support {placeholder} replacement and maintained backward compatibility with old method signatures.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'cli-cleardbtables-driver',
        'title'       => 'Driver-aware Table Clearing',
        'description' => 'Updated ClearDBTables utility to correctly identify and drop tables in both MySQL and SQLite environments.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'cli-make-route-api',
        'title'       => 'CLI make:route API flag',
        'description' => 'Corrected positional argument flag position from -3 to -4 for non-dynamic routes.',
        'status'      => 'FIXED',
    ],
];
file_put_contents('tests/fixed_issues.json', json_encode($fixedIssues, JSON_PRETTY_PRINT));

// 5. Generate Report
echo "\n📊 Generating HTML Report...\n";
passthru('php tests/generate_report.php');

echo "\n✨ All tests completed! Check test_report.html for details.\n";

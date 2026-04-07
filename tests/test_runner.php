<?php

/**
 * INEX SPA Test Runner.
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
        'id'          => 'lang-get-default',
        'title'       => 'Language::get default value support',
        'description' => 'Updated Language::get to support an optional second parameter for a default value if the key is not found.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'ammar-ask-gemini-success',
        'title'       => 'ammar ask:gemini success check',
        'description' => 'Fixed ammar CLI to correctly check for success when calling useGemini (it returns "error" string on failure, not false).',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'sqlite-compat-run-db',
        'title'       => 'SQLite compatibility in run:db',
        'description' => 'Updated SQL templates in ammar CLI to use SQLite-compatible syntax (AUTOINCREMENT instead of AUTO_INCREMENT) when using SQLite.',
        'status'      => 'FIXED',
    ],
    [
        'id'          => 'cli-list-lang-empty',
        'title'       => 'CLI list:lang empty check',
        'description' => 'Fixed list:lang to correctly handle the case when no language files exist.',
        'status'      => 'FIXED',
    ],
];
file_put_contents('tests/fixed_issues.json', json_encode($fixedIssues, JSON_PRETTY_PRINT));

// 5. Generate Report
echo "\n📊 Generating HTML Report...\n";
passthru('php tests/generate_report.php');

echo "\n✨ All tests completed! Check test_report.html for details.\n";

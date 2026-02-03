<?php

// tester/test_runner.php

require_once __DIR__.'/bootstrap.php';

$results = [];

function runTest($name, $callback)
{
    global $results;

    try {
        $callback();
        $results[] = [
            'name'    => $name,
            'status'  => 'passed',
            'message' => 'Test passed successfully.',
        ];
    } catch (Exception $e) {
        $results[] = [
            'name'    => $name,
            'status'  => 'failed',
            'message' => $e->getMessage(),
        ];
    } catch (Error $e) {
        $results[] = [
            'name'    => $name,
            'status'  => 'failed',
            'message' => $e->getMessage(),
        ];
    }
}

function assertEquals($expected, $actual, $message = '')
{
    if ($expected !== $actual) {
        $msg = $message ?: 'Expected '.var_export($expected, true).' but got '.var_export($actual, true);

        throw new Exception($msg);
    }
}

function assertTrue($condition, $message = '')
{
    if ($condition !== true) {
        $msg = $message ?: 'Expected true but got '.var_export($condition, true);

        throw new Exception($msg);
    }
}

function assertFalse($condition, $message = '')
{
    if ($condition !== false) {
        $msg = $message ?: 'Expected false but got '.var_export($condition, true);

        throw new Exception($msg);
    }
}

// Load all tests
$testFiles = glob(__DIR__.'/tests/**/*.php');
foreach ($testFiles as $file) {
    require_once $file;
}

// Save results to a JSON file for the report generator
file_put_contents(__DIR__.'/test_results.json', json_encode($results, JSON_PRETTY_PRINT));

echo 'Tests completed. Total: '.count($results)."\n";
$passed = count(array_filter($results, fn ($r) => $r['status'] === 'passed'));
$failed = count(array_filter($results, fn ($r) => $r['status'] === 'failed'));
echo "Passed: $passed\n";
echo "Failed: $failed\n";

if ($failed > 0) {
    // exit(1); // Don't exit yet, we want to generate report
}

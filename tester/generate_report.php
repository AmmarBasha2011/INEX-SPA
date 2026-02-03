<?php

// tester/generate_report.php

require_once __DIR__.'/bootstrap.php';

$resultsFile = __DIR__.'/test_results.json';
if (!file_exists($resultsFile)) {
    exit("Results file not found. Run test_runner.php first.\n");
}

$results = json_decode(file_get_contents($resultsFile), true);

$passedCount = count(array_filter($results, fn ($r) => $r['status'] === 'passed'));
$failedCount = count(array_filter($results, fn ($r) => $r['status'] === 'failed'));
$totalCount = count($results);

$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Framework Test Report</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 1000px; margin: 0 auto; padding: 20px; background-color: #f4f4f4; }
        h1 { color: #2c3e50; text-align: center; }
        .summary { display: flex; justify-content: space-around; background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .summary-item { text-align: center; }
        .summary-item .number { font-size: 2em; font-weight: bold; display: block; }
        .passed { color: #27ae60; }
        .failed { color: #c0392b; }
        .total { color: #2980b9; }
        .test-list { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .test-item { border-bottom: 1px solid #eee; padding: 10px 0; }
        .test-item:last-child { border-bottom: none; }
        .test-header { display: flex; justify-content: space-between; align-items: center; cursor: pointer; }
        .test-name { font-weight: bold; }
        .test-status { padding: 5px 10px; border-radius: 4px; font-size: 0.8em; text-transform: uppercase; color: #fff; }
        .status-passed { background-color: #27ae60; }
        .status-failed { background-color: #c0392b; }
        .test-details { margin-top: 10px; padding: 10px; background: #f9f9f9; border-left: 4px solid #ddd; display: none; }
        .test-item.open .test-details { display: block; }
        .filter-buttons { margin-bottom: 10px; text-align: center; }
        button { padding: 10px 20px; margin: 0 5px; cursor: pointer; border: none; border-radius: 4px; background: #2980b9; color: #fff; }
        button:hover { background: #3498db; }
    </style>
</head>
<body>
    <h1>🚀 Framework Test Report</h1>

    <div class="summary">
        <div class="summary-item">
            <span class="number total">{$totalCount}</span>
            <span>Total Tests</span>
        </div>
        <div class="summary-item">
            <span class="number passed">{$passedCount}</span>
            <span>Passed</span>
        </div>
        <div class="summary-item">
            <span class="number failed">{$failedCount}</span>
            <span>Failed</span>
        </div>
    </div>

    <div class="filter-buttons">
        <button onclick="filterTests('all')">Show All</button>
        <button onclick="filterTests('passed')" style="background-color: #27ae60;">Show Passed</button>
        <button onclick="filterTests('failed')" style="background-color: #c0392b;">Show Failed</button>
    </div>

    <div class="test-list">
HTML;

foreach ($results as $index => $test) {
    $statusClass = $test['status'] === 'passed' ? 'status-passed' : 'status-failed';
    $message = htmlspecialchars($test['message']);
    $name = htmlspecialchars($test['name']);
    $status = $test['status'];

    $html .= <<<HTML
        <div class="test-item" data-status="{$status}">
            <div class="test-header" onclick="this.parentElement.classList.toggle('open')">
                <span class="test-name">{$name}</span>
                <span class="test-status {$statusClass}">{$status}</span>
            </div>
            <div class="test-details">
                <strong>Why:</strong> {$message}
            </div>
        </div>
HTML;
}

$html .= <<<'HTML'
    </div>

    <script>
        function filterTests(status) {
            const items = document.querySelectorAll('.test-item');
            items.forEach(item => {
                if (status === 'all' || item.getAttribute('data-status') === status) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
HTML;

file_put_contents(PROJECT_ROOT.'/test-report.html', $html);
echo "Report generated: test-report.html\n";

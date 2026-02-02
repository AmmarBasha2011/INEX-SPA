<?php

// tester/generate_report.php

$results = json_decode(file_get_contents(__DIR__.'/results.json'), true);

$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INEX SPA Test Report</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; }
        .summary { display: flex; justify-content: space-around; margin-bottom: 20px; padding: 10px; border-bottom: 1px solid #ddd; }
        .summary div { text-align: center; }
        .summary .count { font-size: 24px; font-weight: bold; }
        .summary .label { color: #666; }
        .passed { color: #28a745; }
        .failed { color: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; }
        tr:hover { background-color: #f1f1f1; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .status-passed { background-color: #d4edda; color: #155724; }
        .status-failed { background-color: #f8d7da; color: #721c24; }
        .reason { font-size: 13px; color: #666; font-style: italic; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 INEX SPA Framework Test Report</h1>
';

$total = count($results);
$passedCount = 0;
$failedCount = 0;
foreach ($results as $res) {
    if ($res['status'] === 'passed') {
        $passedCount++;
    } else {
        $failedCount++;
    }
}

$html .= '
        <div class="summary">
            <div><div class="count">'.$total.'</div><div class="label">Total Tests</div></div>
            <div><div class="count passed">'.$passedCount.'</div><div class="label">Passed</div></div>
            <div><div class="count failed">'.$failedCount.'</div><div class="label">Failed</div></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Test Name</th>
                    <th>Status</th>
                    <th>Message / Reason</th>
                </tr>
            </thead>
            <tbody>
';

foreach ($results as $res) {
    $statusClass = ($res['status'] === 'passed') ? 'status-passed' : 'status-failed';
    $html .= '
                <tr>
                    <td><strong>'.htmlspecialchars($res['test']).'</strong></td>
                    <td><span class="status-badge '.$statusClass.'">'.$res['status'].'</span></td>
                    <td class="reason">'.htmlspecialchars($res['message']).'</td>
                </tr>';
}

$html .= '
            </tbody>
        </table>
        <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #999;">
            Report generated on '.date('Y-m-d H:i:s').'
        </div>
    </div>
</body>
</html>';

file_put_contents(dirname(__DIR__).'/test-report.html', $html);
echo "Report generated: test-report.html\n";

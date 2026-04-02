<?php

$cliRes = json_decode(file_get_contents('tests/cli_results.json'), true);
$coreRes = json_decode(file_get_contents('tests/core_results.json'), true);
$webRes = json_decode(file_get_contents('tests/web_results.json'), true);

$total = count($cliRes) + count($coreRes) + count($webRes);
$passed = 0;
foreach ($cliRes as $res) if ($res['success']) $passed++;
foreach ($coreRes as $res) if ($res['success']) $passed++;
foreach ($webRes as $res) if ($res['success']) $passed++;
$failed = $total - $passed;

ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INEX SPA Test Report</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; text-align: center; }
        .summary { display: flex; justify-content: space-around; margin-bottom: 30px; padding: 20px; background: #ecf0f1; border-radius: 8px; }
        .summary-item { text-align: center; }
        .summary-item .number { font-size: 24px; font-weight: bold; display: block; }
        .summary-item .label { color: #7f8c8d; font-size: 14px; }
        .test-section { margin-bottom: 40px; }
        .test-section h2 { border-bottom: 2px solid #3498db; padding-bottom: 10px; color: #3498db; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #eee; }
        th { background-color: #f9f9f9; }
        .status { font-weight: bold; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .status-success { background-color: #2ecc71; color: white; }
        .status-error { background-color: #e74c3c; color: white; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; font-size: 12px; overflow-x: auto; max-height: 100px; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 INEX SPA Framework Test Report</h1>

        <div class="summary">
            <div class="summary-item">
                <span class="number"><?= $total ?></span>
                <span class="label">Total Tests</span>
            </div>
            <div class="summary-item">
                <span class="number" style="color: #2ecc71;"><?= $passed ?></span>
                <span class="label">Passed</span>
            </div>
            <div class="summary-item">
                <span class="number" style="color: #e74c3c;"><?= $failed ?></span>
                <span class="label">Failed</span>
            </div>
            <div class="summary-item">
                <span class="number" style="color: #f39c12;">3</span>
                <span class="label">Fixed</span>
            </div>
        </div>

        <div class="test-section">
            <h2>CLI Commands</h2>
            <table>
                <thead>
                    <tr>
                        <th>Test Name</th>
                        <th>Status</th>
                        <th>Output</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cliRes as $name => $res): ?>
                    <tr>
                        <td><?= htmlspecialchars($name) ?></td>
                        <td><span class="status <?= $res['success'] ? 'status-success' : 'status-error' ?>"><?= $res['success'] ? 'SUCCESS' : 'FAILED' ?></span></td>
                        <td><pre><?= htmlspecialchars($res['output']) ?></pre></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="test-section">
            <h2>Core Classes & Utilities</h2>
            <table>
                <thead>
                    <tr>
                        <th>Test Name</th>
                        <th>Status</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($coreRes as $name => $res): ?>
                    <tr>
                        <td><?= htmlspecialchars($name) ?></td>
                        <td><span class="status <?= $res['success'] ? 'status-success' : 'status-error' ?>"><?= $res['success'] ? 'SUCCESS' : 'FAILED' ?></span></td>
                        <td><?= htmlspecialchars($res['message']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="test-section">
            <h2>Web & Routing</h2>
            <table>
                <thead>
                    <tr>
                        <th>Route</th>
                        <th>Status</th>
                        <th>Response Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($webRes as $name => $res): ?>
                    <tr>
                        <td><?= htmlspecialchars($name) ?></td>
                        <td><span class="status <?= $res['success'] ? 'status-success' : 'status-error' ?>"><?= $res['success'] ? 'SUCCESS' : 'FAILED' ?></span></td>
                        <td><?= $res['status'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="test-section">
            <h2>Fixed Issues</h2>
            <ul>
                <li><strong>CLI make:route index:</strong> Fixed undefined <code>$filename</code> variable causing crash.</li>
                <li><strong>CLI clear:db:tables:</strong> Fixed command not executing the <code>ClearDBTables::run()</code> method.</li>
                <li><strong>CLI clear:cache:</strong> Fixed incorrect directory path using local "cache/" instead of <code>CACHE_FOLDER</code> constant.</li>
            </ul>
        </div>
    </div>
</body>
</html>
<?php
$html = ob_get_clean();
file_put_contents('test_report.html', $html);

<?php

$cliRes = json_decode(file_get_contents('tests/cli_results.json'), true);
$coreRes = json_decode(file_get_contents('tests/core_results.json'), true);
$webRes = json_decode(file_get_contents('tests/web_results.json'), true);
$fixedRes = json_decode(file_get_contents('tests/fixed_issues.json'), true);

$total = count($cliRes) + count($coreRes) + count($webRes);
$passed = 0;
foreach ($cliRes as $res) if ($res['success']) $passed++;
foreach ($coreRes as $res) if ($res['success']) $passed++;
foreach ($webRes as $res) if ($res['success']) $passed++;
$failed = $total - $passed;
$fixed = count($fixedRes);

ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INEX SPA Framework Test Report</title>
    <style>
        :root {
            --primary: #3498db;
            --success: #2ecc71;
            --danger: #e74c3c;
            --warning: #f39c12;
            --bg: #f4f7f6;
            --card-bg: #ffffff;
            --text: #333;
            --text-light: #7f8c8d;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg);
            margin: 0;
            padding: 0;
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: #2c3e50;
            color: white;
            padding: 30px 20px;
            position: fixed;
            height: 100vh;
        }

        .sidebar h1 {
            font-size: 20px;
            margin-bottom: 40px;
            text-align: center;
        }

        .nav-item {
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.1);
        }

        .nav-item.active {
            background: var(--primary);
        }

        /* Main Content */
        .main {
            margin-left: 260px;
            flex: 1;
            padding: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        /* Dashboard Cards */
        .dashboard {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .card {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            text-align: center;
        }

        .card .number {
            font-size: 32px;
            font-weight: 700;
            display: block;
            margin-bottom: 5px;
        }

        .card .label {
            color: var(--text-light);
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        /* Sections */
        .section {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .section h2 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
            font-size: 18px;
            color: var(--primary);
        }

        /* Tables */
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #eee; }
        th { background: #f9f9f9; font-size: 13px; color: var(--text-light); }

        .status {
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            text-transform: uppercase;
        }
        .status-success { background: #e6f9ed; color: var(--success); }
        .status-error { background: #fdeaea; color: var(--danger); }
        .status-fixed { background: #fff4e5; color: var(--warning); }

        pre {
            background: #272822;
            color: #f8f8f2;
            padding: 10px;
            border-radius: 6px;
            font-size: 11px;
            max-height: 150px;
            overflow: auto;
        }

        /* Filtering */
        .filters {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .filter-btn {
            padding: 6px 15px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
        }

        .filter-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Fixed Issues List */
        .fixed-list {
            list-style: none;
            padding: 0;
        }

        .fixed-item {
            padding: 15px;
            border-left: 4px solid var(--warning);
            background: #fffcf8;
            margin-bottom: 15px;
            border-radius: 0 6px 6px 0;
        }

        .fixed-item h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
        }

        .fixed-item p {
            margin: 0;
            font-size: 14px;
            color: var(--text-light);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h1>🚀 INEX SPA</h1>
        <div class="nav-item active">Dashboard</div>
        <div class="nav-item">CLI Commands</div>
        <div class="nav-item">Core Classes</div>
        <div class="nav-item">Web Routes</div>
        <div class="nav-item">Fixed Issues</div>
    </div>

    <div class="main">
        <div class="header">
            <h2>Framework Health Dashboard</h2>
            <span style="color: var(--text-light); font-size: 14px;">Report Generated: <?= date('Y-m-d H:i:s') ?></span>
        </div>

        <div class="dashboard">
            <div class="card">
                <span class="number" style="color: var(--primary);"><?= $total ?></span>
                <span class="label">Total Tests</span>
            </div>
            <div class="card">
                <span class="number" style="color: var(--success);"><?= $passed ?></span>
                <span class="label">Passed</span>
            </div>
            <div class="card">
                <span class="number" style="color: var(--danger);"><?= $failed ?></span>
                <span class="label">Failed</span>
            </div>
            <div class="card">
                <span class="number" style="color: var(--warning);"><?= $fixed ?></span>
                <span class="label">Fixed</span>
            </div>
        </div>

        <div class="section">
            <h2>CLI Commands</h2>
            <div class="filters">
                <button class="filter-btn active" onclick="filterTable('cli', 'all')">All</button>
                <button class="filter-btn" onclick="filterTable('cli', 'success')">Success</button>
                <button class="filter-btn" onclick="filterTable('cli', 'failed')">Failed</button>
            </div>
            <table id="cli-table">
                <thead>
                    <tr>
                        <th>Test Name</th>
                        <th>Status</th>
                        <th>Output</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cliRes as $name => $res):
                        $statusClass = $res['success'] ? 'status-success' : 'status-error';
                        $statusText = $res['success'] ? 'SUCCESS' : 'FAILED';
                        // Check if it was a solved error (it passes now but is in fixed issues)
                        foreach($fixedRes as $fixed) {
                            if (strpos(strtolower($fixed['title']), strtolower($name)) !== false && $res['success']) {
                                $statusClass = 'status-fixed';
                                $statusText = 'SOLVED';
                                break;
                            }
                        }
                    ?>
                    <tr class="test-row" data-status="<?= $res['success'] ? 'success' : 'failed' ?>">
                        <td><?= htmlspecialchars($name) ?></td>
                        <td><span class="status <?= $statusClass ?>"><?= $statusText ?></span></td>
                        <td><pre><?= htmlspecialchars(substr($res['output'], 0, 500)) ?></pre></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="section">
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
                    <?php foreach ($coreRes as $name => $res):
                        $statusClass = $res['success'] ? 'status-success' : 'status-error';
                        $statusText = $res['success'] ? 'SUCCESS' : 'FAILED';
                        // Logic for Solved Errors in Core
                        foreach($fixedRes as $fixed) {
                            if (strpos(strtolower($fixed['title']), strtolower($name)) !== false && $res['success']) {
                                $statusClass = 'status-fixed';
                                $statusText = 'SOLVED';
                                break;
                            }
                        }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($name) ?></td>
                        <td><span class="status <?= $statusClass ?>"><?= $statusText ?></span></td>
                        <td><?= htmlspecialchars($res['message']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2>Web Routes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Route Name</th>
                        <th>Status</th>
                        <th>HTTP Status</th>
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

        <div class="section">
            <h2>Fixed Issues</h2>
            <div class="fixed-list">
                <?php foreach ($fixedRes as $issue): ?>
                <div class="fixed-item">
                    <h3><?= htmlspecialchars($issue['title']) ?> <span class="status status-fixed">Fixed</span></h3>
                    <p><?= htmlspecialchars($issue['description']) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        function filterTable(tableId, status) {
            const table = document.getElementById(tableId + '-table');
            const rows = table.querySelectorAll('.test-row');
            const buttons = document.querySelectorAll('.filter-btn');

            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            rows.forEach(row => {
                if (status === 'all' || row.dataset.status === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
<?php
$html = ob_get_clean();
file_put_contents('test_report.html', $html);

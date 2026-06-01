<?php

$cliRes = json_decode(file_get_contents('tests/cli_results.json'), true) ?: [];
$coreRes = json_decode(file_get_contents('tests/core_results.json'), true) ?: [];
$webRes = json_decode(file_get_contents('tests/web_results.json'), true) ?: [];
$fixedRes = json_decode(file_get_contents('tests/fixed_issues.json'), true) ?: [];

$fixedIds = array_column($fixedRes, 'id');

function getStatusClass($success, $testId, $fixedIds) {
    if (in_array($testId, $fixedIds)) return 'status-fixed';
    if ($success) return 'status-success';
    return 'status-unsolvable';
}

function getStatusLabel($success, $testId, $fixedIds) {
    if (in_array($testId, $fixedIds)) return 'SOLVED';
    if ($success) return 'SUCCESS';
    return 'UNSOLVABLE';
}

$total = count($cliRes) + count($coreRes) + count($webRes);
$solved = 0;
$passed = 0;
$failed = 0;

foreach ($cliRes as $id => $res) {
    $testId = 'cli-'.$id;
    if (in_array($testId, $fixedIds)) $solved++;
    elseif ($res['success']) $passed++;
    else $failed++;
}

foreach ($coreRes as $id => $res) {
    $testId = 'core-'.$id;
    if (in_array($testId, $fixedIds)) $solved++;
    elseif ($res['success']) $passed++;
    else $failed++;
}

foreach ($webRes as $id => $res) {
    $testId = 'web-'.$id;
    if (in_array($testId, $fixedIds)) $solved++;
    elseif ($res['success']) $passed++;
    else $failed++;
}

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
            --primary: #6366f1;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text: #1e293b;
            --text-light: #64748b;
            --sidebar-bg: #0f172a;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg);
            margin: 0;
            padding: 0;
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            color: white;
            padding: 40px 20px;
            position: fixed;
            height: 100vh;
            box-sizing: border-box;
        }

        .sidebar h1 {
            font-size: 24px;
            margin-bottom: 40px;
            text-align: center;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .nav-item {
            padding: 14px 20px;
            margin-bottom: 8px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
            display: flex;
            align-items: center;
            color: #94a3b8;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.05);
            color: white;
        }

        .nav-item.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .main {
            margin-left: 280px;
            flex: 1;
            padding: 60px;
            max-width: 1200px;
        }

        .header {
            margin-bottom: 40px;
        }

        .header h2 {
            font-size: 30px;
            font-weight: 800;
            margin: 0 0 10px 0;
            letter-spacing: -1px;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-bottom: 60px;
        }

        .card {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            text-align: center;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card .number {
            font-size: 40px;
            font-weight: 800;
            display: block;
            margin-bottom: 8px;
        }

        .card .label {
            color: var(--text-light);
            text-transform: uppercase;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .section {
            background: var(--card-bg);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }

        .section h2 {
            margin-top: 0;
            padding-bottom: 20px;
            margin-bottom: 30px;
            font-size: 22px;
            font-weight: 700;
            border-bottom: 2px solid #f1f5f9;
        }

        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 16px; border-bottom: 1px solid #f1f5f9; }
        th { font-size: 14px; font-weight: 600; color: var(--text-light); }

        .status {
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 11px;
            display: inline-block;
        }
        .status-success { background: #dcfce7; color: #166534; }
        .status-fixed { background: #fef3c7; color: #92400e; }
        .status-unsolvable { background: #fee2e2; color: #991b1b; }

        pre {
            background: #1e293b;
            color: #f8fafc;
            padding: 16px;
            border-radius: 12px;
            font-size: 12px;
            max-height: 200px;
            overflow: auto;
            margin: 0;
        }

        .fixed-item {
            padding: 24px;
            background: #fffbeb;
            border: 1px solid #fef3c7;
            margin-bottom: 20px;
            border-radius: 16px;
        }

        .fixed-item h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
            color: #92400e;
        }

        .fixed-item p {
            margin: 0;
            font-size: 15px;
            color: #b45309;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h1>🚀 INEX SPA</h1>
        <div class="nav-item active" onclick="showSection(event, 'dashboard-section')">Dashboard</div>
        <div class="nav-item" onclick="showSection(event, 'cli-section')">CLI Commands</div>
        <div class="nav-item" onclick="showSection(event, 'core-section')">Core Classes</div>
        <div class="nav-item" onclick="showSection(event, 'web-section')">Web Routes</div>
        <div class="nav-item" onclick="showSection(event, 'fixed-section')">Fixed Issues</div>
    </div>

    <div class="main">
        <div id="dashboard-section" class="report-section">
            <div class="header">
                <h2>Framework Health</h2>
                <div style="color: var(--text-light); font-size: 14px;">Report Generated: <?= date('Y-m-d H:i:s') ?></div>
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
                    <span class="number" style="color: var(--warning);"><?= $solved ?></span>
                    <span class="label">Solved</span>
                </div>
                <div class="card">
                    <span class="number" style="color: var(--danger);"><?= $failed ?></span>
                    <span class="label">Unsolvable</span>
                </div>
            </div>
        </div>

        <div id="cli-section" class="report-section section" style="display:none;">
            <h2>CLI Commands</h2>
            <table>
                <thead>
                    <tr>
                        <th style="width: 25%">Command</th>
                        <th style="width: 15%">Status</th>
                        <th>Output</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cliRes as $name => $res): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($name) ?></code></td>
                        <td>
                            <span class="status <?= getStatusClass($res['success'], 'cli-'.$name, $fixedIds) ?>">
                                <?= getStatusLabel($res['success'], 'cli-'.$name, $fixedIds) ?>
                            </span>
                        </td>
                        <td><pre><?= htmlspecialchars(substr($res['output'], 0, 500)) ?></pre></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="core-section" class="report-section section" style="display:none;">
            <h2>Core Classes</h2>
            <table>
                <thead>
                    <tr>
                        <th style="width: 25%">Class / Method</th>
                        <th style="width: 15%">Status</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($coreRes as $name => $res): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($name) ?></code></td>
                        <td>
                            <span class="status <?= getStatusClass($res['success'], 'core-'.$name, $fixedIds) ?>">
                                <?= getStatusLabel($res['success'], 'core-'.$name, $fixedIds) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($res['message']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="web-section" class="report-section section" style="display:none;">
            <h2>Web Routes</h2>
            <table>
                <thead>
                    <tr>
                        <th style="width: 25%">Route</th>
                        <th style="width: 15%">Status</th>
                        <th>HTTP Code</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($webRes as $name => $res): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($name) ?></code></td>
                        <td>
                            <span class="status <?= getStatusClass($res['success'], 'web-'.$name, $fixedIds) ?>">
                                <?= getStatusLabel($res['success'], 'web-'.$name, $fixedIds) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($res['status'] ?? 'N/A') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="fixed-section" class="report-section section" style="display:none;">
            <h2>Fixed Issues Registry</h2>
            <?php foreach ($fixedRes as $issue): ?>
            <div class="fixed-item">
                <h3><?= htmlspecialchars($issue['title']) ?></h3>
                <p><?= htmlspecialchars($issue['description']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function showSection(event, id) {
            document.querySelectorAll('.report-section').forEach(s => s.style.display = 'none');
            document.getElementById(id).style.display = 'block';
            document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
            event.currentTarget.classList.add('active');
        }
    </script>
</body>
</html>
<?php
$html = ob_get_clean();
file_put_contents('test_report.html', $html);

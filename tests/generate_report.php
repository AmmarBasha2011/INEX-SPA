<?php

$cliRes = json_decode(file_get_contents('tests/cli_results.json'), true) ?? [];
$coreRes = json_decode(file_get_contents('tests/core_results.json'), true) ?? [];
$webRes = json_decode(file_get_contents('tests/web_results.json'), true) ?? [];
$fixedRes = json_decode(file_get_contents('tests/fixed_issues.json'), true) ?? [];

$total = count($cliRes) + count($coreRes) + count($webRes);
$passed = 0;
$unsolvable = 0;

foreach ($cliRes as $res) {
    if ($res['success']) $passed++;
    elseif (isset($res['unsolvable']) && $res['unsolvable']) $unsolvable++;
}
foreach ($coreRes as $res) {
    if ($res['success']) $passed++;
    elseif (isset($res['unsolvable']) && $res['unsolvable']) $unsolvable++;
}
foreach ($webRes as $res) {
    if ($res['success']) $passed++;
    elseif (isset($res['unsolvable']) && $res['unsolvable']) $unsolvable++;
}

$failed = $total - $passed - $unsolvable;
$fixed = count($fixedRes);

ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INEX SPA Framework Test Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --sidebar-bg: #0f172a;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg);
            margin: 0;
            display: flex;
            min-height: 100vh;
            color: var(--text-main);
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            color: white;
            padding: 2rem 1.5rem;
            position: fixed;
            height: 100vh;
            box-sizing: border-box;
            z-index: 100;
        }

        .sidebar h1 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-item {
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #94a3b8;
            text-decoration: none;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.05);
            color: white;
        }

        .nav-item.active {
            background: var(--primary);
            color: white;
        }

        /* Main Content */
        .main {
            margin-left: 280px;
            flex: 1;
            padding: 2.5rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h2 {
            font-size: 1.875rem;
            font-weight: 700;
            margin: 0;
        }

        /* Dashboard */
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .stat-card .label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .stat-card .value {
            font-size: 2.25rem;
            font-weight: 800;
        }

        /* Sections */
        .section {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 2.5rem;
            scroll-margin-top: 2.5rem;
        }

        .section h3 {
            margin-top: 0;
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Tables */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-success { background: #dcfce7; color: #166534; }
        .badge-error { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #dbeafe; color: #1e40af; }

        pre {
            background: #1e293b;
            color: #f8fafc;
            padding: 1rem;
            border-radius: 0.5rem;
            font-size: 0.8125rem;
            margin: 0;
            max-height: 200px;
            overflow: auto;
        }

        /* Fixed Issues */
        .issue-card {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            background: #f8fafc;
            border-left: 4px solid var(--success);
        }

        .issue-card h4 {
            margin: 0 0 0.5rem 0;
            font-size: 1.125rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .issue-card p {
            margin: 0;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* Filter */
        .filters {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        @media (max-width: 1024px) {
            .sidebar { width: 80px; padding: 2rem 0.5rem; }
            .sidebar h1 span, .nav-item span { display: none; }
            .main { margin-left: 80px; }
            .nav-item { justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h1><i class="fas fa-rocket"></i> <span>INEX SPA</span></h1>
        <a href="#dashboard" class="nav-item active" onclick="setActive(this)"><i class="fas fa-chart-line"></i> <span>Dashboard</span></a>
        <a href="#cli" class="nav-item" onclick="setActive(this)"><i class="fas fa-terminal"></i> <span>CLI Commands</span></a>
        <a href="#core" class="nav-item" onclick="setActive(this)"><i class="fas fa-microchip"></i> <span>Core Classes</span></a>
        <a href="#web" class="nav-item" onclick="setActive(this)"><i class="fas fa-globe"></i> <span>Web Routes</span></a>
        <a href="#fixed" class="nav-item" onclick="setActive(this)"><i class="fas fa-tools"></i> <span>Fixed Issues</span></a>
    </div>

    <div class="main">
        <div id="dashboard">
            <div class="header">
                <h2>Framework Health Dashboard</h2>
                <div class="badge badge-info"><i class="far fa-clock"></i> <?= date('Y-m-d H:i:s') ?></div>
            </div>

            <div class="dashboard">
                <div class="stat-card">
                    <span class="label">Total Tests</span>
                    <span class="value" style="color: var(--primary);"><?= $total ?></span>
                </div>
                <div class="stat-card">
                    <span class="label">Success</span>
                    <span class="value" style="color: var(--success);"><?= $passed ?></span>
                </div>
                <div class="stat-card">
                    <span class="label">Solved Errors</span>
                    <span class="value" style="color: var(--warning);"><?= $fixed ?></span>
                </div>
                <div class="stat-card">
                    <span class="label">Unsolvable</span>
                    <span class="value" style="color: var(--danger);"><?= $unsolvable ?></span>
                </div>
            </div>
        </div>

        <div id="cli" class="section">
            <h3><i class="fas fa-terminal"></i> CLI Commands</h3>
            <div class="filters">
                <button class="filter-btn active" onclick="filterTable('cli', 'all', this)">All</button>
                <button class="filter-btn" onclick="filterTable('cli', 'success', this)">Success</button>
                <button class="filter-btn" onclick="filterTable('cli', 'error', this)">Failed</button>
            </div>
            <div class="table-container">
                <table id="cli-table">
                    <thead>
                        <tr>
                            <th>Test Case</th>
                            <th>Status</th>
                            <th>Output</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cliRes as $name => $res): ?>
                        <tr class="test-row" data-status="<?= $res['success'] ? 'success' : 'error' ?>">
                            <td style="font-weight: 600;"><?= htmlspecialchars($name) ?></td>
                            <td>
                                <?php if ($res['success']): ?>
                                    <span class="badge badge-success">SUCCESS</span>
                                <?php elseif (isset($res['unsolvable']) && $res['unsolvable']): ?>
                                    <span class="badge badge-error">UNSOLVABLE</span>
                                <?php else: ?>
                                    <span class="badge badge-error">FAILED</span>
                                <?php endif; ?>
                            </td>
                            <td><pre><?= htmlspecialchars($res['output']) ?></pre></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="core" class="section">
            <h3><i class="fas fa-microchip"></i> Core Classes & Utilities</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Test Case</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($coreRes as $name => $res): ?>
                        <tr>
                            <td style="font-weight: 600;"><?= htmlspecialchars($name) ?></td>
                            <td>
                                <span class="badge <?= $res['success'] ? 'badge-success' : 'badge-error' ?>">
                                    <?= $res['success'] ? 'SUCCESS' : 'FAILED' ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($res['message']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="web" class="section">
            <h3><i class="fas fa-globe"></i> Web Routes</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Route</th>
                            <th>Status Code</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($webRes as $name => $res): ?>
                        <tr>
                            <td style="font-weight: 600;"><?= htmlspecialchars($name) ?></td>
                            <td><span class="badge badge-info"><?= $res['status'] ?></span></td>
                            <td>
                                <span class="badge <?= $res['success'] ? 'badge-success' : 'badge-error' ?>">
                                    <?= $res['success'] ? 'PASS' : 'FAIL' ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="fixed" class="section">
            <h3><i class="fas fa-tools"></i> Solved Errors</h3>
            <?php foreach ($fixedRes as $issue): ?>
            <div class="issue-card">
                <h4>
                    <?= htmlspecialchars($issue['title']) ?>
                    <span class="badge badge-success">FIXED</span>
                </h4>
                <p><?= htmlspecialchars($issue['description']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function setActive(el) {
            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
            el.classList.add('active');
        }

        function filterTable(tableId, status, btn) {
            const table = document.getElementById(tableId + '-table');
            const rows = table.querySelectorAll('.test-row');

            btn.parentElement.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

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

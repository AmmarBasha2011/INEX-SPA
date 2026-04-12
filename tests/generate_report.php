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
    <title>INEX SPA - Ultimate Framework Test Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --sidebar-bg: #0f172a;
        }

        * {
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            margin: 0;
            padding: 0;
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            color: white;
            padding: 2rem 1.5rem;
            position: fixed;
            height: 100vh;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 3rem;
            color: white;
            text-decoration: none;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }

        .nav-item {
            padding: 12px 16px;
            margin-bottom: 8px;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #94a3b8;
            font-weight: 500;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.05);
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
            max-width: 1400px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 2.5rem;
        }

        .header h2 {
            margin: 0;
            font-size: 1.875rem;
            font-weight: 700;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }

        .stat-card.total::after { background: var(--info); }
        .stat-card.passed::after { background: var(--success); }
        .stat-card.failed::after { background: var(--danger); }
        .stat-card.fixed::after { background: var(--warning); }

        .stat-card .value {
            font-size: 2.25rem;
            font-weight: 800;
            display: block;
            margin-bottom: 4px;
        }

        .stat-card .label {
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        /* Content Sections */
        .section-card {
            background: var(--card-bg);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            margin-bottom: 2.5rem;
            border: 1px solid var(--border);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .section-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
        }

        /* Table Styling */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        th {
            text-align: left;
            padding: 1rem;
            background: #f8fafc;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            font-weight: 600;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid var(--border);
            vertical-align: top;
        }

        tr:last-child td {
            border-bottom: none;
        }

        /* Status Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }

        pre {
            background: #1e293b;
            color: #e2e8f0;
            padding: 1rem;
            border-radius: 12px;
            font-family: 'Fira Code', monospace;
            font-size: 0.8125rem;
            max-height: 200px;
            overflow: auto;
            margin: 0;
        }

        /* Fixed Issues Items */
        .fixed-item {
            padding: 1.5rem;
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 16px;
            margin-bottom: 1rem;
        }

        .fixed-item h4 {
            margin: 0 0 0.5rem 0;
            font-size: 1.125rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .fixed-item p {
            margin: 0;
            color: #92400e;
            font-size: 0.9375rem;
            line-height: 1.5;
        }

        /* Tab System */
        .tabs {
            display: flex;
            gap: 8px;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 12px;
        }

        .tab-btn {
            padding: 6px 16px;
            border-radius: 8px;
            border: none;
            background: transparent;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
        }

        .tab-btn.active {
            background: white;
            color: var(--primary);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        @media (max-width: 1024px) {
            .sidebar { width: 80px; padding: 2rem 1rem; }
            .sidebar-brand span, .nav-item span { display: none; }
            .main { margin-left: 80px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="#" class="sidebar-brand">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="32" height="32" rx="8" fill="#6366F1"/>
                <path d="M10 10L22 22M22 10L10 22" stroke="white" stroke-width="3" stroke-linecap="round"/>
            </svg>
            <span>INEX SPA</span>
        </a>
        <nav class="nav-menu">
            <div class="nav-item active" onclick="scrollToSection('dashboard')">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                <span>Dashboard</span>
            </div>
            <div class="nav-item" onclick="scrollToSection('cli')">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="4 17 10 11 4 5"></polyline><line x1="12" y1="19" x2="20" y2="19"></line></svg>
                <span>CLI Tests</span>
            </div>
            <div class="nav-item" onclick="scrollToSection('core')">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"></path><line x1="16" y1="8" x2="2" y2="22"></line><line x1="17.5" y1="15" x2="9" y2="15"></line></svg>
                <span>Core Library</span>
            </div>
            <div class="nav-item" onclick="scrollToSection('web')">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                <span>Web Routes</span>
            </div>
        </nav>
    </div>

    <div class="main">
        <header class="header">
            <div id="dashboard">
                <p style="color: var(--text-muted); font-weight: 600; margin: 0; text-transform: uppercase; font-size: 0.75rem;">Framework Analysis</p>
                <h2>Test Execution Report</h2>
            </div>
            <div style="text-align: right;">
                <span class="badge badge-success" style="padding: 8px 16px;">System Online</span>
                <p style="color: var(--text-muted); font-size: 0.8125rem; margin: 8px 0 0 0;"><?= date('l, F j, Y - H:i:s') ?></p>
            </div>
        </header>

        <div class="stats-grid">
            <div class="stat-card total">
                <span class="label">Total Scenarios</span>
                <span class="value"><?= $total ?></span>
            </div>
            <div class="stat-card passed">
                <span class="label">Passed</span>
                <span class="value" style="color: var(--success);"><?= $passed ?></span>
            </div>
            <div class="stat-card failed">
                <span class="label">Failed</span>
                <span class="value" style="color: var(--danger);"><?= $failed ?></span>
            </div>
            <div class="stat-card fixed">
                <span class="label">Fixed Issues</span>
                <span class="value" style="color: var(--warning);"><?= $fixed ?></span>
            </div>
        </div>

        <!-- CLI SECTION -->
        <div class="section-card" id="cli">
            <div class="section-header">
                <h3>Ammar CLI Commands</h3>
                <div class="tabs">
                    <button class="tab-btn active" onclick="filterTable('cli', 'all')">All</button>
                    <button class="tab-btn" onclick="filterTable('cli', 'success')">Passed</button>
                    <button class="tab-btn" onclick="filterTable('cli', 'failed')">Failed</button>
                </div>
            </div>
            <div class="table-container">
                <table id="cli-table">
                    <thead>
                        <tr>
                            <th>Command / Test</th>
                            <th>Status</th>
                            <th>Execution Logs</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cliRes as $name => $res): ?>
                        <tr class="test-row" data-status="<?= $res['success'] ? 'success' : 'failed' ?>">
                            <td style="font-weight: 600; color: var(--text-main);"><?= htmlspecialchars($name) ?></td>
                            <td><span class="badge <?= $res['success'] ? 'badge-success' : 'badge-danger' ?>"><?= $res['success'] ? 'Passed' : 'Failed' ?></span></td>
                            <td><pre><?= htmlspecialchars($res['output']) ?></pre></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CORE SECTION -->
        <div class="section-card" id="core">
            <div class="section-header">
                <h3>Core Framework Classes</h3>
                <span class="badge badge-success"><?= count($coreRes) ?> Units Tested</span>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Class / Function</th>
                            <th>Status</th>
                            <th>Assertion Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($coreRes as $name => $res): ?>
                        <tr>
                            <td style="font-weight: 600;"><?= htmlspecialchars($name) ?></td>
                            <td><span class="badge <?= $res['success'] ? 'badge-success' : 'badge-danger' ?>"><?= $res['success'] ? 'Passed' : 'Failed' ?></span></td>
                            <td style="color: var(--text-muted); font-size: 0.875rem;"><?= htmlspecialchars($res['message']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- WEB SECTION -->
        <div class="section-card" id="web">
            <div class="section-header">
                <h3>Routing & Web Integration</h3>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Endpoint</th>
                            <th>Status</th>
                            <th>HTTP Code</th>
                            <th>Response Preview</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($webRes as $name => $res): ?>
                        <tr>
                            <td style="font-weight: 600;"><?= htmlspecialchars($name) ?></td>
                            <td><span class="badge <?= $res['success'] ? 'badge-success' : 'badge-danger' ?>"><?= $res['success'] ? 'Passed' : 'Failed' ?></span></td>
                            <td><code><?= $res['status'] ?></code></td>
                            <td><pre><?= htmlspecialchars(substr($res['response'], 0, 200)) ?>...</pre></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FIXED ISSUES -->
        <div class="section-card">
            <div class="section-header">
                <h3>Resolved During Testing</h3>
            </div>
            <div class="fixed-list">
                <?php foreach ($fixedRes as $issue): ?>
                <div class="fixed-item">
                    <h4>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" color="var(--success)"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <?= htmlspecialchars($issue['title']) ?>
                    </h4>
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
            const buttons = document.querySelectorAll('.tab-btn');

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

        function scrollToSection(id) {
            document.getElementById(id).scrollIntoView({ behavior: 'smooth' });

            // Update active state in sidebar
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('onclick').includes(id)) {
                    item.classList.add('active');
                }
            });
        }
    </script>
</body>
</html>
<?php
$html = ob_get_clean();
file_put_contents('test_report.html', $html);

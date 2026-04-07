<?php

$cli_results = json_decode(file_get_contents('tests/cli_results.json'), true) ?: [];
$core_results = json_decode(file_get_contents('tests/core_results.json'), true) ?: [];
$web_results = json_decode(file_get_contents('tests/web_results.json'), true) ?: [];
$fixed_issues = json_decode(file_get_contents('tests/fixed_issues.json'), true) ?: [];
$unsolvable_issues = json_decode(file_get_contents('tests/unsolvable_issues.json'), true) ?: [];

ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INEX SPA Framework Test Report</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 1200px; margin: 0 auto; padding: 20px; background-color: #f4f7f6; }
        header { background: #2c3e50; color: #fff; padding: 20px; border-radius: 8px; margin-bottom: 30px; text-align: center; }
        h1 { margin: 0; }
        .summary { display: flex; justify-content: space-around; margin-bottom: 30px; }
        .summary-item { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; flex: 1; margin: 0 10px; }
        .summary-item h3 { margin-top: 0; }
        .count { font-size: 2em; font-weight: bold; }
        .success { color: #27ae60; }
        .error { color: #e74c3c; }
        .fixed { color: #2980b9; }
        .unsolvable { color: #f39c12; }
        section { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 30px; }
        h2 { border-bottom: 2px solid #eee; padding-bottom: 10px; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f9f9f9; }
        .status-badge { padding: 5px 10px; border-radius: 4px; font-size: 0.8em; font-weight: bold; text-transform: uppercase; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-error { background: #f8d7da; color: #721c24; }
        .badge-fixed { background: #cce5ff; color: #004085; }
        .badge-unsolvable { background: #fff3cd; color: #856404; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; font-size: 0.9em; overflow-x: auto; max-height: 200px; }
        .accordion { cursor: pointer; padding: 10px; background: #eee; border: none; width: 100%; text-align: left; outline: none; transition: 0.4s; margin-top: 5px; }
        .panel { padding: 0 18px; display: none; background-color: white; overflow: hidden; }
    </style>
</head>
<body>
    <header>
        <h1>INEX SPA Framework Test Report</h1>
        <p>Generated on: <?php echo date('Y-m-d H:i:s'); ?></p>
    </header>

    <div class="summary">
        <?php
        $total = count($cli_results) + count($core_results) + count($web_results);
        $passed = 0;
        foreach($cli_results as $r) if($r['success']) $passed++;
        foreach($core_results as $r) if($r['success']) $passed++;
        foreach($web_results as $r) if($r['success']) $passed++;
        ?>
        <div class="summary-item">
            <h3>Total Tests</h3>
            <div class="count"><?php echo $total; ?></div>
        </div>
        <div class="summary-item">
            <h3>Passed</h3>
            <div class="count success"><?php echo $passed; ?></div>
        </div>
        <div class="summary-item">
            <h3>Fixed</h3>
            <div class="count fixed"><?php echo count($fixed_issues); ?></div>
        </div>
        <div class="summary-item">
            <h3>Failed</h3>
            <div class="count error"><?php echo $total - $passed; ?></div>
        </div>
    </div>

    <?php if (!empty($fixed_issues)): ?>
    <section>
        <h2 class="fixed">Solved Issues</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fixed_issues as $issue): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($issue['title']); ?></strong></td>
                    <td><?php echo htmlspecialchars($issue['description']); ?></td>
                    <td><span class="status-badge badge-fixed">FIXED</span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
    <?php endif; ?>

    <?php if (!empty($unsolvable_issues)): ?>
    <section>
        <h2 class="unsolvable">Unsolvable / Environment Issues</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($unsolvable_issues as $issue): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($issue['title']); ?></strong></td>
                    <td><?php echo htmlspecialchars($issue['description']); ?></td>
                    <td><span class="status-badge badge-unsolvable">UNSOLVABLE</span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
    <?php endif; ?>

    <section>
        <h2>CLI Tests</h2>
        <table>
            <thead>
                <tr>
                    <th>Test</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cli_results as $name => $result): ?>
                <tr>
                    <td><?php echo htmlspecialchars($name); ?></td>
                    <td>
                        <span class="status-badge <?php echo $result['success'] ? 'badge-success' : 'badge-error'; ?>">
                            <?php echo $result['success'] ? 'SUCCESS' : 'FAILURE'; ?>
                        </span>
                    </td>
                    <td>
                        <button class="accordion">View Output</button>
                        <div class="panel">
                            <pre><?php echo htmlspecialchars($result['output']); ?></pre>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section>
        <h2>Core Tests</h2>
        <table>
            <thead>
                <tr>
                    <th>Test</th>
                    <th>Status</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($core_results as $name => $result): ?>
                <tr>
                    <td><?php echo htmlspecialchars($name); ?></td>
                    <td>
                        <span class="status-badge <?php echo $result['success'] ? 'badge-success' : 'badge-error'; ?>">
                            <?php echo $result['success'] ? 'SUCCESS' : 'FAILURE'; ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($result['message']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section>
        <h2>Web Tests</h2>
        <table>
            <thead>
                <tr>
                    <th>Test</th>
                    <th>Status</th>
                    <th>HTTP Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($web_results as $name => $result): ?>
                <tr>
                    <td><?php echo htmlspecialchars($name); ?></td>
                    <td>
                        <span class="status-badge <?php echo $result['success'] ? 'badge-success' : 'badge-error'; ?>">
                            <?php echo $result['success'] ? 'SUCCESS' : 'FAILURE'; ?>
                        </span>
                    </td>
                    <td><?php echo $result['status']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <script>
        var acc = document.getElementsByClassName("accordion");
        for (var i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.display === "block") {
                    panel.style.display = "none";
                } else {
                    panel.style.display = "block";
                }
            });
        }
    </script>
</body>
</html>
<?php
$html = ob_get_clean();
file_put_contents('test_report.html', $html);
echo "HTML Report generated: test_report.html\n";

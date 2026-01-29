<?php

function strip_ansi($text)
{
    return preg_replace('/\x1b[[][^A-Za-z]*[A-Za-z]/', '', $text);
}

$commands = [
    'LIST' => [
        'list'        => [],
        'list:cron'   => [],
        'list:db'     => [],
        'list:import' => [],
        'list:lang'   => [],
        'list:routes' => [],
    ],
    'MAKE' => [
        'make:route'   => ['-1' => 'testroute', '-2' => 'no', '-3' => 'GET', '-4' => 'no'],
        'make:cache'   => ['-1' => 'testkey', '-2' => 'testvalue', '-3' => '3600'],
        'make:db'      => ['-1' => 'create', '-2' => 'testtable'],
        'make:lang'    => ['-1' => 'en'],
        'make:layout'  => ['-1' => 'main'],
        'make:session' => ['-1' => 'user', '-2' => 'ammar'],
        'make:cron'    => ['-1' => 'mytask'],
        'make:sitemap' => [],
        'make:auth'    => [],
    ],
    'GET/UPDATE' => [
        'get:cache'    => ['-1' => 'testkey'],
        'update:cache' => ['-1' => 'testkey', '-2' => 'newvalue'],
        'get:session'  => ['-1' => 'user'],
    ],
    'RUN' => [
        'run:cron' => ['-1' => 'mytask'],
        'run:db'   => [],
    ],
    'DELETE' => [
        'delete:cache'   => ['-1' => 'testkey'],
        'delete:db'      => ['-1' => 'create', '-2' => 'testtable'],
        'delete:lang'    => ['-1' => 'en'],
        'delete:session' => ['-1' => 'user'],
        'delete:cron'    => ['-1' => 'mytask'],
        'delete:import'  => ['-1' => 'inex-spa-helper'],
    ],
    'CLEAR' => [
        'clear:cache'  => [],
        'clear:routes' => [],
        'clear:db'     => [],
        'clear:cron'   => [],
        'clear:docs'   => [],
        'clear:start'  => [],
    ],
    'OTHER' => [
        'ask:gemini'     => ['-1' => 'Hi'],
        'install:import' => ['-1' => 'https://github.com/AmmarBasha2011/inex-spa-helper.git'],
        'serve'          => ['-1' => '8081'],
    ],
];

$results = [];
$startTimeOverall = microtime(true);

foreach ($commands as $category => $cmds) {
    foreach ($cmds as $cmd => $args) {
        echo "Running $cmd...\n";
        $argString = '';
        foreach ($args as $k => $v) {
            $argString .= " $k $v";
        }

        $startTime = microtime(true);
        $timestamp = date('Y-m-d H:i:s');

        if ($cmd === 'serve') {
            $descriptorspec = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ];
            $process = proc_open("php ammar serve $argString", $descriptorspec, $pipes);
            if (is_resource($process)) {
                sleep(2);
                $output = 'Server started on port 8081 (Terminated after 2s)';
                $exitCode = 0;
                proc_terminate($process);
                proc_close($process);
            } else {
                $output = 'Failed to start server';
                $exitCode = 1;
            }
        } else {
            if (in_array($cmd, ['delete:import', 'install:import', 'delete:db'])) {
                $output = shell_exec("echo \"yes\" | php ammar $cmd $argString 2>&1");
            } else {
                $output = shell_exec("php ammar $cmd $argString 2>&1");
            }
            $exitCode = (strpos($output, "\033[0;31m ✘") !== false) ? 1 : 0;
        }

        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 4);

        $results[] = [
            'category'  => $category,
            'command'   => $cmd,
            'args'      => $argString,
            'output'    => strip_ansi($output),
            'status'    => $exitCode === 0 ? 'Passed' : 'Failed',
            'timestamp' => $timestamp,
            'duration'  => $duration,
        ];
    }
}

$endTimeOverall = microtime(true);
$totalDuration = round($endTimeOverall - $startTimeOverall, 2);

$passedCount = 0;
foreach ($results as $res) {
    if ($res['status'] === 'Passed') {
        $passedCount++;
    }
}
$failedCount = count($results) - $passedCount;
$totalCount = count($results);

$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ammar CLI Detailed Test Report</title>
    <style>
        :root {
            --primary: #007bff;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
            --dark: #343a40;
            --light: #f8f9fa;
        }
        body { font-family: \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; background-color: #f0f2f5; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 1100px; margin: auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid var(--light); padding-bottom: 20px; }
        h1 { color: var(--primary); margin: 0; font-size: 2.5em; }
        .report-meta { color: #666; margin-top: 10px; font-size: 0.9em; }

        .summary { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .summary-card { padding: 20px; border-radius: 10px; text-align: center; color: white; transition: transform 0.2s; }
        .summary-card:hover { transform: translateY(-5px); }
        .summary-card.passed { background: linear-gradient(135deg, #28a745, #218838); }
        .summary-card.failed { background: linear-gradient(135deg, #dc3545, #c82333); }
        .summary-card.total { background: linear-gradient(135deg, #007bff, #0069d9); }
        .summary-card.duration { background: linear-gradient(135deg, #6c757d, #5a6268); }
        .summary-card .count { font-size: 2.5em; font-weight: bold; display: block; }
        .summary-card .label { text-transform: uppercase; letter-spacing: 1px; font-size: 0.8em; opacity: 0.9; }

        .filters { text-align: center; margin-bottom: 30px; display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.2s; }
        .btn-all { background: var(--dark); color: white; }
        .btn-passed { background: var(--success); color: white; }
        .btn-failed { background: var(--danger); color: white; }
        .btn:hover { opacity: 0.9; box-shadow: 0 2px 8px rgba(0,0,0,0.15); }

        .category-title { background: var(--light); padding: 10px 15px; margin-top: 30px; border-radius: 6px; border-left: 5px solid var(--primary); font-weight: bold; font-size: 1.2em; color: var(--dark); }

        .command-item { border: 1px solid #e1e4e8; border-radius: 8px; margin-top: 15px; overflow: hidden; background: #fff; }
        .command-header { padding: 15px 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: background 0.2s; }
        .command-header:hover { background: #fcfcfc; }
        .command-info { display: flex; align-items: center; gap: 15px; }
        .command-name { font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace; font-weight: 600; color: #0366d6; }
        .badge { padding: 4px 10px; border-radius: 12px; font-size: 0.75em; font-weight: 700; text-transform: uppercase; }
        .badge-passed { background: #dcffe4; color: #155724; }
        .badge-failed { background: #ffdce0; color: #721c24; }

        .meta-tags { display: flex; gap: 10px; font-size: 0.75em; color: #6a737d; }
        .tag { display: flex; align-items: center; gap: 4px; }

        .command-details { padding: 0 20px 20px; display: none; border-top: 1px solid #e1e4e8; background: #fafbfc; }
        .details-content { margin-top: 15px; }
        .args-box { background: #fff; border: 1px solid #ddd; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 0.9em; }
        .args-label { font-weight: bold; color: #555; margin-right: 10px; }
        pre { background: #24292e; color: #e1e4e8; padding: 20px; border-radius: 6px; overflow-x: auto; font-size: 0.9em; line-height: 1.45; margin: 0; }

        footer { margin-top: 50px; text-align: center; color: #888; font-size: 0.85em; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🚀 Ammar CLI Detailed Report</h1>
            <div class="report-meta">Generated on '.date('F j, Y, g:i a').' | Framework Version: 5 Beta</div>
        </header>

        <div class="summary">
            <div class="summary-card total">
                <span class="count">'.$totalCount.'</span>
                <span class="label">Total Tests</span>
            </div>
            <div class="summary-card passed">
                <span class="count">'.$passedCount.'</span>
                <span class="label">Passed</span>
            </div>
            <div class="summary-card failed">
                <span class="count">'.$failedCount.'</span>
                <span class="label">Failed</span>
            </div>
            <div class="summary-card duration">
                <span class="count">'.$totalDuration.'s</span>
                <span class="label">Total Duration</span>
            </div>
        </div>

        <div class="filters">
            <button class="btn btn-all" onclick="filter(\'all\')">All Commands</button>
            <button class="btn btn-passed" onclick="filter(\'Passed\')">Passed Only</button>
            <button class="btn btn-failed" onclick="filter(\'Failed\')">Failed Only</button>
        </div>';

$currentCategory = '';
foreach ($results as $index => $res) {
    if ($res['category'] !== $currentCategory) {
        $currentCategory = $res['category'];
        $html .= '<div class="category-title">'.$currentCategory.'</div>';
    }

    $statusBadge = $res['status'] === 'Passed' ? 'badge-passed' : 'badge-failed';

    $html .= '
        <div class="command-item" data-status="'.$res['status'].'">
            <div class="command-header" onclick="toggleDetails('.$index.')">
                <div class="command-info">
                    <span class="badge '.$statusBadge.'">'.$res['status'].'</span>
                    <span class="command-name">ammar '.$res['command'].'</span>
                </div>
                <div class="meta-tags">
                    <span class="tag">⏱ '.$res['duration'].'s</span>
                    <span class="tag">📅 '.$res['timestamp'].'</span>
                </div>
            </div>
            <div id="details-'.$index.'" class="command-details">
                <div class="details-content">
                    '.($res['args'] ? '<div class="args-box"><span class="args-label">Arguments:</span>'.htmlspecialchars($res['args']).'</div>' : '').'
                    <pre>'.htmlspecialchars($res['output']).'</pre>
                </div>
            </div>
        </div>';
}

$html .= '
        <footer>
            INEX Team &copy; '.date('Y').' | High-performance PHP Solutions
        </footer>
    </div>

    <script>
        function toggleDetails(index) {
            const details = document.getElementById(\'details-\' + index);
            const isVisible = details.style.display === \'block\';
            details.style.display = isVisible ? \'none\' : \'block\';
        }

        function filter(status) {
            const items = document.querySelectorAll(\'.command-item\');
            const categories = document.querySelectorAll(\'.category-title\');

            items.forEach(item => {
                if (status === \'all\' || item.getAttribute(\'data-status\') === status) {
                    item.style.display = \'block\';
                } else {
                    item.style.display = \'none\';
                }
            });

            // Hide empty categories
            categories.forEach(cat => {
                let next = cat.nextElementSibling;
                let visible = false;
                while(next && next.classList.contains(\'command-item\')) {
                    if (next.style.display !== \'none\') {
                        visible = true;
                        break;
                    }
                    next = next.nextElementSibling;
                }
                cat.style.display = visible ? \'block\' : \'none\';
            });
        }
    </script>
</body>
</html>';

file_put_contents('ammar_cli_test_report.html', $html);
echo "Report generated: ammar_cli_test_report.html\n";

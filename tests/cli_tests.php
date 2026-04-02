<?php

$tests = [
    'list' => [
        'command'  => 'php ammar list',
        'expected' => 'Available commands:',
    ],
    'make:db' => [
        'command'    => 'php ammar make:db -1 create -2 users',
        'expected'   => 'DB file created:',
        'check_file' => 'db/createusersTable_*.sql',
    ],
    'make:route' => [
        'command'    => 'php ammar make:route -1 testroute -2 no -3 GET -4 no',
        'expected'   => 'Route file created: testroute_request_GET.ahmed.php',
        'check_file' => 'web/testroute_request_GET.ahmed.php',
    ],
    'fix:route' => [
        'command'  => 'echo "<?php echo \"testroute content\"; ?>" > web/testroute_request_GET.ahmed.php',
        'expected' => '',
    ],
    'make:cache' => [
        'command'  => 'php ammar make:cache -1 mykey -2 myvalue -3 3600',
        'expected' => 'Cache entry created for key: mykey',
    ],
    'get:cache' => [
        'command'  => 'php ammar get:cache -1 mykey',
        'expected' => 'Cache value: myvalue',
    ],
    'update:cache' => [
        'command'  => 'php ammar update:cache -1 mykey -2 newvalue',
        'expected' => 'Cache updated for key: mykey',
    ],
    'get:cache_updated' => [
        'command'  => 'php ammar get:cache -1 mykey',
        'expected' => 'Cache value: newvalue',
    ],
    'delete:cache' => [
        'command'  => 'php ammar delete:cache -1 mykey',
        'expected' => 'Cache deleted for key: mykey',
    ],
    'make:session' => [
        'command'  => 'php ammar make:session -1 sesskey -2 sessvalue',
        'expected' => 'Session Created!!!',
    ],
    'get:session' => [
        'command'  => 'php ammar get:session -1 sesskey',
        'expected' => 'Session Value: sessvalue',
    ],
    'delete:session' => [
        'command'  => 'php ammar delete:session -1 sesskey',
        'expected' => 'Session Deleted!!!',
    ],
    'make:lang' => [
        'command'    => 'php ammar make:lang -1 fr',
        'expected'   => 'Language file created:',
        'check_file' => 'lang/fr.json',
    ],
    'list:lang' => [
        'command'  => 'php ammar list:lang',
        'expected' => '- fr',
    ],
    'delete:lang' => [
        'command'  => 'php ammar delete:lang -1 fr',
        'expected' => 'Deleted language file:',
    ],
    'make:layout' => [
        'command'    => 'php ammar make:layout -1 custom',
        'expected'   => 'Layout file created:',
        'check_file' => 'layouts/custom.ahmed.php',
    ],
    'make:auth' => [
        'command'    => 'php ammar make:auth',
        'expected'   => 'DB file created:',
        'check_file' => 'db/createusersTable_*.sql',
    ],
    'list:routes' => [
        'command'  => 'php ammar list:routes',
        'expected' => 'testroute_request_GET.ahmed.php',
    ],
    'list:db' => [
        'command'  => 'php ammar list:db',
        'expected' => 'createusersTable',
    ],
];

$results = [];

foreach ($tests as $name => $test) {
    echo "Running test: $name...\n";
    $output = shell_exec($test['command'].' 2>&1');
    $success = strpos($output, $test['expected']) !== false;

    if ($success && isset($test['check_file'])) {
        $files = glob($test['check_file']);
        if (empty($files)) {
            $success = false;
            $output .= "\n[Error] File not found: ".$test['check_file'];
        }
    }

    $results[$name] = [
        'success' => $success,
        'output'  => $output,
    ];

    if ($success) {
        echo "✅ Passed\n";
    } else {
        echo "❌ Failed\n";
        echo "Output: $output\n";
    }
}

file_put_contents('tests/cli_results.json', json_encode($results, JSON_PRETTY_PRINT));

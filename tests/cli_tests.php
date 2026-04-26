<?php

/**
 * CLI Tests for INEX SPA.
 * Covers all commands found in 'php ammar list'.
 */

$tests = [
    'list' => [
        'command'  => 'php ammar list',
        'expected' => 'Available commands:',
    ],
    'make:db' => [
        'command'    => 'php ammar make:db -1 create -2 users_test',
        'expected'   => 'DB file created:',
        'check_file' => 'db/createusers_testTable_*.sql',
    ],
    'run:db' => [
        'command'  => 'php ammar run:db',
        'expected' => 'Success executing:',
    ],
    'list:db' => [
        'command'  => 'php ammar list:db',
        'expected' => 'createusers_testTable',
    ],
    'make:route' => [
        'command'    => 'php ammar make:route -1 testroute_test -2 no -3 GET -4 no',
        'expected'   => 'Route file created: testroute_test_request_GET.ahmed.php',
        'check_file' => 'web/testroute_test_request_GET.ahmed.php',
    ],
    'make:route_api' => [
        'command'    => 'php ammar make:route -1 testapi_test -2 no -3 GET -4 yes',
        'expected'   => 'Route file created: testapi_test_request_GET_api.ahmed.php',
        'check_file' => 'web/testapi_test_request_GET_api.ahmed.php',
    ],
    'list:routes' => [
        'command'  => 'php ammar list:routes',
        'expected' => 'testroute_test_request_GET.ahmed.php',
    ],
    'make:cache' => [
        'command'  => 'php ammar make:cache -1 mykey_test -2 myvalue_test -3 3600',
        'expected' => 'Cache entry created for key: mykey_test',
    ],
    'get:cache' => [
        'command'  => 'php ammar get:cache -1 mykey_test',
        'expected' => 'Cache value: myvalue_test',
    ],
    'update:cache' => [
        'command'  => 'php ammar update:cache -1 mykey_test -2 newvalue_test',
        'expected' => 'Cache updated for key: mykey_test',
    ],
    'delete:cache' => [
        'command'  => 'php ammar delete:cache -1 mykey_test',
        'expected' => 'Cache deleted for key: mykey_test',
    ],
    'make:session' => [
        'command'  => 'php ammar make:session -1 sesskey_test -2 sessvalue_test',
        'expected' => 'Session Created!!!',
    ],
    'get:session' => [
        'command'  => 'php ammar get:session -1 sesskey_test',
        'expected' => 'Session Value: sessvalue_test',
    ],
    'delete:session' => [
        'command'  => 'php ammar delete:session -1 sesskey_test',
        'expected' => 'Session Deleted!!!',
    ],
    'make:lang' => [
        'command'    => 'php ammar make:lang -1 es_test',
        'expected'   => 'Language file created:',
        'check_file' => 'lang/es_test.json',
    ],
    'list:lang' => [
        'command'  => 'php ammar list:lang',
        'expected' => '- es_test',
    ],
    'delete:lang' => [
        'command'  => 'php ammar delete:lang -1 es_test',
        'expected' => 'Deleted language file:',
    ],
    'make:layout' => [
        'setup'      => 'rm -f layouts/custom_test.ahmed.php',
        'command'    => 'php ammar make:layout -1 custom_test',
        'expected'   => 'Layout file created:',
        'check_file' => 'layouts/custom_test.ahmed.php',
    ],
    'make:auth' => [
        'command'    => 'php ammar make:auth',
        'expected'   => 'DB file created:',
        'check_file' => 'db/createusersTable_*.sql',
    ],
    'make:cron' => [
        'command'    => 'php ammar make:cron TestTask',
        'expected'   => 'Cron task file created:',
        'check_file' => 'core/cron/tasks/TestTask.php',
    ],
    'list:cron' => [
        'command'  => 'php ammar list:cron',
        'expected' => '- TestTask',
    ],
    'run:cron' => [
        'command'  => 'php ammar run:cron TestTask',
        'expected' => 'executed successfully',
    ],
    'delete:cron' => [
        'command'  => 'echo "yes" | php ammar delete:cron TestTask',
        'expected' => 'deleted successfully',
    ],
    'make:sitemap' => [
        'command'  => 'php ammar make:sitemap',
        'expected' => 'Sitemap generated!',
    ],
    'install:import' => [
        'command'  => 'php ammar install:import -1 tests/mock_repo',
        'expected' => 'successfully',
    ],
    'list:import' => [
        'command'  => 'php ammar list:import',
        'expected' => 'mock_repo',
    ],
    'delete:import' => [
        'command'  => 'echo "yes" | php ammar delete:import -1 mock_repo',
        'expected' => 'Import deleted:',
    ],
    'clear:cache' => [
        'command'  => 'php ammar clear:cache',
        'expected' => 'Cache cleared!',
    ],
    'clear:db' => [
        'command'  => 'php ammar clear:db',
        'expected' => 'DB files cleared!',
    ],
    'clear:routes' => [
        'command'  => 'php ammar clear:routes',
        'expected' => 'Route files cleared!',
    ],
    'clear:db:tables' => [
        'command'  => 'echo "yes" | php ammar clear:db:tables',
        'expected' => 'All tables in database',
    ],
];

$results = [];

foreach ($tests as $name => $test) {
    if (isset($test['setup'])) {
        shell_exec($test['setup']);
    }
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
    echo ($success ? '✅ ' : '❌ ').$name."\n";
}

file_put_contents('tests/cli_results.json', json_encode($results, JSON_PRETTY_PRINT));

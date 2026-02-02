<?php
// tester/test_runner.php

require_once __DIR__ . '/bootstrap.php';

class TestRunner {
    private $results = [];

    public function run($testFile) {
        $testName = basename($testFile, '.php');
        require_once $testFile;

        if (!class_exists($testName)) {
            $this->results[] = [
                'test' => $testName,
                'status' => 'failed',
                'message' => "Class $testName not found in $testFile"
            ];
            return;
        }

        $testInstance = new $testName();
        $methods = get_class_methods($testInstance);

        foreach ($methods as $method) {
            if (strpos($method, 'test') === 0) {
                try {
                    $testInstance->$method();
                    $this->results[] = [
                        'test' => "$testName::$method",
                        'status' => 'passed',
                        'message' => 'Success'
                    ];
                } catch (Exception $e) {
                    $this->results[] = [
                        'test' => "$testName::$method",
                        'status' => 'failed',
                        'message' => $e->getMessage()
                    ];
                } catch (Error $e) {
                    $this->results[] = [
                        'test' => "$testName::$method",
                        'status' => 'failed',
                        'message' => $e->getMessage()
                    ];
                }
            }
        }
    }

    public function getResults() {
        return $this->results;
    }

    public function saveResults($path) {
        file_put_contents($path, json_encode($this->results, JSON_PRETTY_PRINT));
    }
}

abstract class TestCase {
    protected function assertEquals($expected, $actual, $message = '') {
        if ($expected !== $actual) {
            $msg = "Expected " . var_export($expected, true) . ", but got " . var_export($actual, true);
            if ($message) $msg .= " - $message";
            throw new Exception($msg);
        }
    }

    protected function assertTrue($condition, $message = '') {
        if ($condition !== true) {
            $msg = "Expected true, but got " . var_export($condition, true);
            if ($message) $msg .= " - $message";
            throw new Exception($msg);
        }
    }

    protected function assertFalse($condition, $message = '') {
        if ($condition !== false) {
            $msg = "Expected false, but got " . var_export($condition, true);
            if ($message) $msg .= " - $message";
            throw new Exception($msg);
        }
    }

    protected function assertNull($condition, $message = '') {
        if ($condition !== null) {
            $msg = "Expected null, but got " . var_export($condition, true);
            if ($message) $msg .= " - $message";
            throw new Exception($msg);
        }
    }
}

$runner = new TestRunner();
$testFiles = glob(__DIR__ . '/*Test.php');

foreach ($testFiles as $file) {
    $runner->run($file);
}

$runner->saveResults(__DIR__ . '/results.json');
echo "Tests completed. Results saved to tester/results.json\n";

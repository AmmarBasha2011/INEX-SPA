<?php

class LoggerTest extends TestCase
{
    public function testLog()
    {
        Logger::clearLogs();
        Logger::log('error', 'Test Error Message');

        $logFile = PROJECT_ROOT.'/core/logs/errors.log';
        $this->assertTrue(file_exists($logFile));
        $this->assertTrue(str_contains(file_get_contents($logFile), 'Test Error Message'));
    }

    public function testClearLogs()
    {
        Logger::log('system', 'System Message');
        Logger::clearLogs();
        $logFile = PROJECT_ROOT.'/core/logs/system.log';
        $this->assertEquals('', file_get_contents($logFile));
    }
}

<?php

use PHPUnit\Framework\TestCase;

class ComponentMemoryProfilerTest extends TestCase
{
    public function test_memory_leak_detector()
    {
        // Simulate a component with high memory consumption
        ComponentMemoryProfiler::startComponent('TestComponent');
        $testData = str_repeat('a', 1024 * 1024 * 2); // 2MB
        ComponentMemoryProfiler::endComponent('TestComponent');

        $warnings = ComponentMemoryProfiler::getWarnings();

        $this->assertCount(1, $warnings);
        $this->assertStringContainsString('TestComponent', $warnings[0]);
    }

    public function test_unclosed_subscription()
    {
        ComponentMemoryProfiler::addSubscription('TestSubscription');
        $warnings = ComponentMemoryProfiler::getWarnings();
        $this->assertCount(2, $warnings);
        $this->assertStringContainsString('TestSubscription', $warnings[1]);
    }
}

<?php

class ComponentMemoryProfiler
{
    private static $componentMemory = [];
    private static $memoryThreshold = 1024 * 1024; // 1MB
    private static $subscriptions = [];

    public static function addSubscription($name)
    {
        self::$subscriptions[$name] = true;
    }

    public static function removeSubscription($name)
    {
        if (isset(self::$subscriptions[$name])) {
            unset(self::$subscriptions[$name]);
        }
    }

    public static function startComponent($componentName)
    {
        self::$componentMemory[$componentName] = [
            'start' => memory_get_usage(),
            'end'   => 0,
            'usage' => 0,
        ];
    }

    public static function endComponent($componentName)
    {
        if (isset(self::$componentMemory[$componentName])) {
            self::$componentMemory[$componentName]['end'] = memory_get_usage();
            self::$componentMemory[$componentName]['usage'] = self::$componentMemory[$componentName]['end'] - self::$componentMemory[$componentName]['start'];
        }
    }

    public static function getMemoryUsage()
    {
        return self::$componentMemory;
    }

    public static function getWarnings()
    {
        $warnings = [];
        foreach (self::$componentMemory as $componentName => $memory) {
            if ($memory['usage'] > self::$memoryThreshold) {
                $warnings[] = "Component '{$componentName}' is using a lot of memory: ".round($memory['usage'] / 1024, 2).' KB';
            }
        }

        foreach (self::$subscriptions as $name => $value) {
            $warnings[] = "Subscription '{$name}' was not closed.";
        }

        return $warnings;
    }
}

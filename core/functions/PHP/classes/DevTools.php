<?php

class DevTools
{
    private static $startTime;
    private static $startMemory;
    private static $queries = [];

    public static function start()
    {
        self::$startTime = microtime(true);
        self::$startMemory = memory_get_usage();
    }

    public static function addQuery($query, $params, $time)
    {
        self::$queries[] = [
            'query' => $query,
            'params' => $params,
            'time' => $time,
        ];
    }

    public static function render()
    {
        $endTime = microtime(true);
        $executionTime = ($endTime - self::$startTime) * 1000;
        $memoryUsage = memory_get_peak_usage();

        $output = '<div id="inex-devtools-bar" style="display:none;">';
        $output .= '<div class="inex-menu">';
        $output .= '<button class="inex-tab-button active" onclick="openTab(event, \'performance\')">Performance</button>';
        $output .= '<button class="inex-tab-button" onclick="openTab(event, \'queries\')">Queries</button>';
        $output .= '<button class="inex-tab-button" onclick="openTab(event, \'route\')">Route</button>';
        $output .= '<button class="inex-tab-button" onclick="openTab(event, \'files\')">Files</button>';
        $output .= '</div>';

        // Performance Tab
        $output .= '<div id="performance" class="inex-tab-content" style="display:block;">';
        $output .= '<h3>Performance</h3>';
        $output .= '<table>';
        $output .= '<tr><td>Execution Time</td><td>'.round($executionTime, 2).' ms</td></tr>';
        $output .= '<tr><td>Memory Usage</td><td>'.round($memoryUsage / 1024, 2).' KB</td></tr>';
        $output .= '</table>';
        $output .= '</div>';

        // Queries Tab
        $output .= '<div id="queries" class="inex-tab-content">';
        $output .= '<h3>Database Queries</h3>';
        if (empty(self::$queries)) {
            $output .= '<p>No queries recorded.</p>';
        } else {
            $output .= '<table>';
            $output .= '<tr><th>Query</th><th>Parameters</th><th>Time (ms)</th></tr>';
            foreach (self::$queries as $query) {
                $output .= '<tr><td>'.htmlspecialchars($query['query']).'</td><td>'.json_encode($query['params']).'</td><td>'.round($query['time'], 2).'</td></tr>';
            }
            $output .= '</table>';
        }
        $output .= '</div>';

        // Route Tab
        $output .= '<div id="route" class="inex-tab-content">';
        $output .= '<h3>Route Information</h3>';
        $output .= '<table>';
        $output .= '<tr><td>Route</td><td>'.($_GET['page'] ?? 'index').'</td></tr>';
        $output .= '<tr><td>Method</td><td>'.$_SERVER['REQUEST_METHOD'].'</td></tr>';
        $output .= '</table>';
        $output .= '</div>';

        // Files Tab
        $output .= '<div id="files" class="inex-tab-content">';
        $output .= '<h3>Included Files</h3>';
        $output .= '<ul>';
        foreach (get_included_files() as $file) {
            $output .= '<li>'.$file.'</li>';
        }
        $output .= '</ul>';
        $output .= '</div>';

        $output .= '</div>';

        echo $output;
    }
}

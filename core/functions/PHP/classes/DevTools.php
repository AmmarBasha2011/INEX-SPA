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
            'query'  => $query,
            'params' => $params,
            'time'   => $time,
        ];
    }

    public static function render()
    {
        $endTime = microtime(true);
        $executionTime = ($endTime - self::$startTime) * 1000;
        $memoryUsage = memory_get_peak_usage();

        $output = '<script>
            document.addEventListener(\'keydown\', function(event) {
                if (event.ctrlKey && event.key === \'d\') {
                    var devToolsBar = document.getElementById(\'inex-devtools-bar\');
                    if (devToolsBar.style.display === \'none\') {
                        devToolsBar.style.display = \'block\';
                    } else {
                        devToolsBar.style.display = \'none\';
                    }
                }
            });

            function openTab(event, tabName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("inex-tab-content");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("inex-tab-button");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(tabName).style.display = "block";
                event.currentTarget.className += " active";
            }
        </script>';
        $output .= '<div id="inex-devtools-bar" style="position: fixed; bottom: 0; width: 100%; background-color: #f1f1f1; border-top: 1px solid #ccc; z-index: 9999; display: none;">';
        $output .= '<button onclick="document.getElementById(\'inex-devtools-bar\').style.display=\'none\'" style="position: absolute; top: 0; right: 0;">Close</button>';
        $output .= '<div class="inex-menu">';
        $output .= '<button class="inex-tab-button active" onclick="openTab(event, \'performance\')">Performance</button>';
        $output .= '<button class="inex-tab-button" onclick="openTab(event, \'queries\')">Queries</button>';
        $output .= '<button class="inex-tab-button" onclick="openTab(event, \'route\')">Route</button>';
        $output .= '<button class="inex-tab-button" onclick="openTab(event, \'files\')">Files</button>';
        $output .= '<button class="inex-tab-button" onclick="openTab(event, \'server\')">Server</button>';
        $output .= '<button class="inex-tab-button" onclick="openTab(event, \'session\')">Session</button>';
        $output .= '<button class="inex-tab-button" onclick="openTab(event, \'request\')">Request</button>';
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

        // Add other tabs here...
        $output .= '<div id="server" class="inex-tab-content">';
        $output .= '<h3>Server</h3>';
        $output .= '<table>';
        foreach ($_SERVER as $key => $value) {
            $output .= '<tr><td>'.htmlspecialchars($key).'</td><td>'.htmlspecialchars($value).'</td></tr>';
        }
        $output .= '</table>';
        $output .= '</div>';

        $output .= '<div id="session" class="inex-tab-content">';
        $output .= '<h3>Session</h3>';
        $output .= '<table>';
        if (isset($_SESSION)) {
            foreach ($_SESSION as $key => $value) {
                $output .= '<tr><td>'.htmlspecialchars($key).'</td><td>'.htmlspecialchars(print_r($value, true)).'</td></tr>';
            }
        } else {
            $output .= '<tr><td colspan="2">No session data</td></tr>';
        }
        $output .= '</table>';
        $output .= '</div>';

        $output .= '<div id="request" class="inex-tab-content">';
        $output .= '<h3>Request</h3>';
        $output .= '<table>';
        $output .= '<tr><td>GET</td><td>'.htmlspecialchars(print_r($_GET, true)).'</td></tr>';
        $output .= '<tr><td>POST</td><td>'.htmlspecialchars(print_r($_POST, true)).'</td></tr>';
        $output .= '<tr><td>COOKIE</td><td>'.htmlspecialchars(print_r($_COOKIE, true)).'</td></tr>';
        $output .= '</table>';
        $output .= '</div>';

        $output .= '</div>';
        $output .= '<style>
            #inex-devtools-bar {
                font-family: sans-serif;
            }
            .inex-menu {
                overflow: hidden;
                border-bottom: 1px solid #ccc;
            }
            .inex-tab-button {
                background-color: inherit;
                float: left;
                border: none;
                outline: none;
                cursor: pointer;
                padding: 14px 16px;
                transition: 0.3s;
            }
            .inex-tab-button:hover {
                background-color: #ddd;
            }
            .inex-tab-button.active {
                background-color: #ccc;
            }
            .inex-tab-content {
                display: none;
                padding: 6px 12px;
                border-top: none;
                height: 200px;
                overflow: auto;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
        </style>';

        echo $output;
    }
}

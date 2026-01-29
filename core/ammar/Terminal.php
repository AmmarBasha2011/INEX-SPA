<?php

class Terminal {
    public static $colors = [
        'reset'   => "\033[0m",
        'black'   => "\033[0;30m",
        'red'     => "\033[0;31m",
        'green'   => "\033[0;32m",
        'yellow'  => "\033[0;33m",
        'blue'    => "\033[0;34m",
        'magenta' => "\033[0;35m",
        'cyan'    => "\033[0;36m",
        'white'   => "\033[0;37m",
        'bold'    => "\033[1m",
        'bg_red'  => "\033[41m",
        'bg_green'=> "\033[42m",
        'bg_blue' => "\033[44m",
    ];

    public static function color($text, $color) {
        return (self::$colors[$color] ?? '') . $text . self::$colors['reset'];
    }

    public static function success($text) {
        echo self::color(" ✔ ", 'green') . $text . PHP_EOL;
    }

    public static function error($text) {
        echo self::color(" ✘ ", 'red') . self::color($text, 'red') . PHP_EOL;
    }

    public static function info($text) {
        echo self::color(" ℹ ", 'blue') . $text . PHP_EOL;
    }

    public static function warning($text) {
        echo self::color(" ⚠ ", 'yellow') . self::color($text, 'yellow') . PHP_EOL;
    }

    public static function header($text) {
        $len = strlen($text) + 4;
        echo PHP_EOL;
        echo self::color(str_repeat("━", $len), 'cyan') . PHP_EOL;
        echo self::color("  " . strtoupper($text), 'bold') . PHP_EOL;
        echo self::color(str_repeat("━", $len), 'cyan') . PHP_EOL;
    }
}

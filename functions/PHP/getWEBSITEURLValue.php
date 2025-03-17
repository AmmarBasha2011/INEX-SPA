<?php
function getWEBSITEURLValue() {
    $jscode = 'window.WEBSITE_URL = "' . getEnvValue('WEBSITE_URL') . '";' . "\n";
    $jscode .= 'window.APP_NAME = "' . getEnvValue('APP_NAME') . '";' . "\n";
    return $jscode;
}
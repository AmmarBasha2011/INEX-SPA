<?php
function getWEBSITEURLValue() {
    $jscode = 'window.WEBSITE_URL = "' . getEnvValue('WEBSITE_URL') . '";' . "\n";
    return $jscode;
}
<?php

/**
 * Returns a JavaScript snippet that sets the WEBSITE_URL and APP_NAME global variables.
 *
 * @return string The JavaScript code.
 */
function getWEBSITEURLValue()
{
    $jscode = 'window.WEBSITE_URL = "'.getEnvValue('WEBSITE_URL').'";'."\n";
    $jscode .= 'window.APP_NAME = "'.getEnvValue('APP_NAME').'";'."\n";

    return $jscode;
}

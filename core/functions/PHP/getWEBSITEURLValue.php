<?php

/**
 * Generates JavaScript code to set global WEBSITE_URL and APP_NAME variables.
 *
 * This function retrieves the 'WEBSITE_URL' and 'APP_NAME' values from the
 * environment configuration and returns a string of JavaScript code that

 * sets these values on the client-side `window` object.
 *
 * @return string A string of JavaScript code.
 */
function getWEBSITEURLValue()
{
    $jscode = 'window.WEBSITE_URL = "'.getEnvValue('WEBSITE_URL').'";'."\n";
    $jscode .= 'window.APP_NAME = "'.getEnvValue('APP_NAME').'";'."\n";

    return $jscode;
}

<?php

/**
 * Generates a JavaScript snippet to expose environment variables to the client-side.
 *
 * This function retrieves the `WEBSITE_URL` and `APP_NAME` values from the server's
 * environment configuration and formats them into a string of JavaScript. This script,
 * when executed in a browser, creates global `window.WEBSITE_URL` and `window.APP_NAME`
 * variables, making these server-side settings accessible to client-side scripts.
 *
 * @return string A string containing the JavaScript code snippet.
 */
function getWEBSITEURLValue()
{
    $jscode = 'window.WEBSITE_URL = "'.getEnvValue('WEBSITE_URL').'";'."\n";
    $jscode .= 'window.APP_NAME = "'.getEnvValue('APP_NAME').'";'."\n";

    return $jscode;
}

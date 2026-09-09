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
    // SECURITY: Escape values for JavaScript context
    $jscode = 'window.WEBSITE_URL = "'.htmlspecialchars(getEnvValue('WEBSITE_URL'), ENT_QUOTES, 'UTF-8').'";'."\n";
    $jscode .= 'window.APP_NAME = "'.htmlspecialchars(getEnvValue('APP_NAME'), ENT_QUOTES, 'UTF-8').'";'."\n";

    return $jscode;
}

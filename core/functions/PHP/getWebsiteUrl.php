<?php

/**
 * Retrieves the website URL from the environment configuration.
 *
 * This function is a simple wrapper around `getEnvValue()` to fetch
 * the 'WEBSITE_URL' value.
 *
 * @return string|null The website URL, or null if not found.
 */
function getWebsiteUrl()
{
    return getEnvValue('WEBSITE_URL');
}

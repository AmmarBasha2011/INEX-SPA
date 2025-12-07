<?php

/**
 * Gets the website URL from the .env file.
 *
 * @return string|null The website URL, or null if it is not found.
 */
function getWebsiteUrl()
{
    return getEnvValue('WEBSITE_URL');
}

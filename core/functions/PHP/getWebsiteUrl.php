<?php

/**
 * Retrieves the base URL of the website from the environment configuration.
 *
 * This function serves as a convenient shorthand for `getEnvValue('WEBSITE_URL')`,
 * providing a dedicated way to access this common and important configuration value.
 * The URL should be defined in the .env file.
 *
 * @return string|null The website's base URL as a string, or `null` if the
 *                     `WEBSITE_URL` key is not found in the .env file.
 */
function getWebsiteUrl()
{
    return getEnvValue('WEBSITE_URL');
}

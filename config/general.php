<?php
/**
 * General Configuration for CraftCMS
 */

use craft\config\GeneralConfig;
use craft\helpers\App;

return GeneralConfig::create()
    ->defaultWeekStartDay(1) // Set the default start of the week (0 = Sunday)
    ->omitScriptNameInUrls() // Remove "index.php" from URLs
    ->preloadSingles() // Preload single entries
    ->preventUserEnumeration() // Security feature
    ->aliases([
        '@webroot' => dirname(__DIR__) . '/web',
        'enableCsrfProtection' => false,    
        'allowAdminChanges' => true,
'useProjectConfigFile' => true,

    ])
    ->enableCsrfProtection(true) // Ensure CSRF is enabled
    ->devMode(true) // Enable debug mode (disable on production!)
    ->useEmailAsUsername(true) // Ensure emails can be used as usernames
;

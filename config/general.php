<?php
/**
 * General Configuration for Craft CMS (env-driven & prod-safe)
 */
use craft\config\GeneralConfig;
use craft\helpers\App;

return GeneralConfig::create()

    // --- Site behavior (safe defaults) ---
    ->defaultWeekStartDay(1)                // 0=Sun, 1=Mon
    ->omitScriptNameInUrls(true)            // requires web/.htaccess or nginx rules (Craft provides them)
    ->preloadSingles(true)
    ->preventUserEnumeration(true)

    // --- Env-driven flags (set these in .env / Cloud Variables) ---
    ->cpTrigger(App::env('CP_TRIGGER') ?: 'admin')
    ->devMode((bool) App::env('DEV_MODE'))                      // false on prod
    ->allowAdminChanges((bool) App::env('ALLOW_ADMIN_CHANGES')) // false on prod
    ->disallowRobots((bool) App::env('DISALLOW_ROBOTS'))        // true on dev, false on prod
    ->enableCsrfProtection(App::env('ENABLE_CSRF') !== 'false') // defaults to true
    ->useEmailAsUsername(true)

    // --- Aliases (no hardcoded localhost; read from env) ---
    ->aliases([
        '@webroot'       => dirname(__DIR__) . '/web',
        '@web'           => App::env('PRIMARY_SITE_URL') ?: 'http://localhost:8000',

        // Optional, only if you reference these aliases in templates/plugins:
        '@assetBasePath' => App::env('ASSET_BASE_PATH') ?: '@webroot/assets',
        '@assetBaseUrl'  => App::env('ASSET_BASE_URL')  ?: '@web/assets',
    ]);

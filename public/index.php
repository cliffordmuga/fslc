<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Shared-hosting path support
|--------------------------------------------------------------------------
| On cPanel the web root is public_html/ but Laravel's app lives one level
| above it.  Set APP_LARAVEL_PATH in .htaccess (or cPanel → Env Variables)
| to point at the project root.  Falls back to __DIR__/../ for local dev.
|
| Example .htaccess line (add to public/.htaccess before the RewriteEngine):
|   SetEnv APP_LARAVEL_PATH /home/YOUR_CPANEL_USER/fslc
|
*/
$appPath = rtrim(getenv('APP_LARAVEL_PATH') ?: dirname(__DIR__), '/\\');

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $appPath . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $appPath . '/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $appPath . '/bootstrap/app.php';

$app->handleRequest(Request::capture());

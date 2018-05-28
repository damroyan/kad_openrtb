<?php

use Phalcon\Loader;

$loader = new Loader();

/**
 * Register Namespaces
 */
$loader->registerNamespaces([
    'Tizer\Models' => APP_PATH . '/common/models/',
    'Tizer'        => APP_PATH . '/common/library/',
]);

/**
 * Register module classes
 */
$loader->registerClasses([
    'Tizer\Modules\Frontend\Module' => APP_PATH . '/modules/frontend/Module.php',
    'Tizer\Modules\Cli\Module'      => APP_PATH . '/modules/cli/Module.php'
]);

$loader->register();

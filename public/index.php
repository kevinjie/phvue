<?php

use Phalcon\Mvc\Application;

define('APP_PATH', realpath('..'));

require_once APP_PATH . '/vendor/autoload.php';

try {
    date_default_timezone_set('PRC');

    // load .env
    $dotenv = new Dotenv\Dotenv(APP_PATH);
    $dotenv->load();

    /**
     * Include vendor_dispatcher
     */
    require __DIR__ . '/../apps/config/services.php';

    /**
     * Handle the request
     */
    $application = new Application($di);

    /**
     * Include modules
     */
    require __DIR__ . '/../apps/config/modules.php';

    /**
     * Include routes
     */
    require __DIR__ . '/../apps/config/routes.php';

    echo str_replace(["\n","\r","\t"], '', $application->handle()->getContent());

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}


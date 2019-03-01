<?php

/**
 * Register application modules
 */
$application->registerModules(array(
    'demo' => array(
        'className' => 'Phvue\Demo\Module',
        'path' => __DIR__ . '/../demo/Module.php'
    ),
));

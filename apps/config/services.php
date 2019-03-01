<?php

use Phalcon\Mvc\Router;
use Phalcon\Di\FactoryDefault;
use Phalcon\Session\Adapter\Redis as SessionRedis;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Http\Response\Cookies as Cookies;
use Phalcon\Crypt as Crypt;
use Phalcon\Di;
use Phalcon\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;
use Phalcon\Cache\Backend\Redis as RedisCache;
use Phalcon\Cache\Frontend\Data as FrontData;
use Phvue\Common\Exceptions\ParamsException;
use Phvue\Common\Utils\MemcacheAdapter;

require __DIR__ . '/constants.php';

/**
 * The FactoryDefault Dependency Injector automatically registers the right service_adaptor to provide a full stack framework
 */
if(empty($di)) {
    if (is_cli()) {
        $di = new Phalcon\Di\FactoryDefault\Cli();
    } else {
        $di = new FactoryDefault();
    }
}

Di::setDefault($di);

if(!($di instanceof Phalcon\Di\FactoryDefault\Cli)) {

    /* Registering a router
     */
    $di->setShared('router', function () {
        $router = new Router();

        $router->setDefaultModule('demo');
        $router->setDefaultNamespace('Phvue\Demo\Controllers');

        return $router;
    });

    /**
     * Starts the session the first time some component requests the session service
     * Don't set session service in other place
     */
    $di->setShared('session', function () {
        $config = config('database.redis');

        $redisConfig = [
            'host' => $config['host'],
            'port' => $config['port'],
            'lifetime' => $config['lifetime'],
        ];
        if (!empty($config['auth'])) {
            $redisConfig['auth'] = $config['auth'];
        }

        $session = new SessionRedis($redisConfig);
        $session->start();
        return $session;
    });


    /**
     * Register the session flash service with the Twitter Bootstrap classes
     */
    $di->set('flash', function () {
        return new Flash(array(
            'error' => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice' => 'alert alert-info',
            'warning' => 'alert alert-warning'
        ));
    });

    /**
     * Register dispatcher and set events manager.
     */
    $di->setShared('dispatcher', function () use ($di) {
        $eventsManager = new Phalcon\Events\Manager();

        $eventsManager->attach(
            "dispatch:beforeException",
            function (Event $event, $dispatcher, Exception $exception) use ($di) {
                switch (true) {
                    case $exception instanceof DispatchException :
                        switch ($exception->getCode()) {
                            case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                            case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                                return false;
                        }
                        break;
                    case $exception instanceof ParamsException :
                        // handle params exceptions.
                        if ($di->has('exceptionHandler')) {
                            $di->get('exceptionHandler')->handle($exception);

                            // stop from throwing exception.
                            return false;
                        }
                    default :
                        if ($di->has('exceptionHandler')) {
                            $di->get('exceptionHandler')->handle(new \Exception("系统错误"));

                            // stop from throwing exception.
                            return false;
                        }
                        return false;
                }
                return true;
            }
        );

        $dispatcher = new Phalcon\Mvc\Dispatcher();
        $dispatcher->setEventsManager($eventsManager);
        $dispatcher->setDefaultNamespace('Phvue\Demo\Controllers');
        return $dispatcher;
    });


    $di->setShared('cookies', function () {
        $cookies = new Cookies();

        $cookies->useEncryption(true);

        return $cookies;
    });
}

$di->setShared('crypt', function () {
    $crypt = new Crypt();

    $crypt->setKey(')()*}{<?{|}12364,/,/oqiawhfao#^@'); // Use your own key!

    return $crypt;
});

$di->setShared('memcache', function () {
    $config = config('database.memcache');
    $memcache = new MemcacheAdapter($config);
    return $memcache;
});

$di->setShared('redis', function () use ($di) {
    return new \Phvue\Common\Utils\RedisAdapter();
});


$di->setShared('db', function () {
    $adapter = config('database.adapter');
    $config = config('database.' . $adapter);
    $class = 'Phalcon\Db\Adapter\Pdo\\' . ucfirst($adapter);
    $connection = new $class($config->toArray());
    return $connection;
});

$di->setShared('profiler', function () {
    return new \Phalcon\Db\Profiler();
});



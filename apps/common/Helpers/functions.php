<?php 

function env($key, $default = null)
{
    $value = getenv($key);

    if ($value === false) {
        return $default;
    }

    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'empty':
        case '(empty)':
            return '';
        case 'null':
        case '(null)':
            return;
    }

    return trim($value, '" \'');
}

function config($key = null)
{
    static $configs;

    if ($key === false) {
        $configs = [];
        return;
    }

    $keys = explode('.', $key);
    $fileName = array_shift($keys);
    if (isset($configs[$fileName])) {
        $config = $configs[$fileName];
    } else {
        $file = APP_PATH . '/configs/' . $fileName . '.php';
        if (!file_exists($file)) {
            return null;
        }
        $config = require($file);
    }
    $configs[$fileName] = $config;

    if (!is_array($config) && !$config instanceof ArrayAccess) {
        return null;
    }

    if (count($keys) < 1) {
        return $config;
    }

    foreach ($keys as $key) {
        if ((is_array($config) || $config instanceof ArrayAccess) && isset($config[$key])) {
            $config = $config[$key];
        } else {
            return null;
        }
    }

    return $config;
}

function config_env($confSet)
{
    $env = config('app.APP_ENV');

    if (!($config = config("{$confSet}.{$env}"))) {
        $config = config("{$confSet}.default");
    }

    return $config;
}

function is_cli()
{
    return php_sapi_name() === 'cli';
}

function di($key = null)
{
    $di = Phalcon\Di::getDefault();
    if(empty($di))
        return false;

    if (is_null($key)) {
        return $di;
    }

    if ($di->has($key)) {
        return $di->get($key);
    } else {
        return false;
    }
}
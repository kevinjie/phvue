<?php

namespace Phvue\Common\Utils;

use Phalcon\Di\FactoryDefault\Cli;
use Phalcon\Logger\Adapter\Syslog;
use Phalcon\Logger as LoggerParent;

class Logger extends LoggerParent
{
    /**
     * Sends/Writes a critical message to the log
     *
     * @param string $message
     * @param array $context
     * @return \Phalcon\Logger\AdapterInterface
     */
    public static function critical($message, array $context = null) {
        return self::_write($message, $context, 'critical');
    }

    /**
     * Sends/Writes an emergency message to the log
     *
     * @param string $message
     * @param array $context
     * @return \Phalcon\Logger\AdapterInterface
     */
    public static function emergency($message, array $context = null) {
        return self::_write($message, $context , 'emergency');
    }

    /**
     * Sends/Writes a debug message to the log
     *
     * @param string $message
     * @param array $context
     * @return \Phalcon\Logger\AdapterInterface
     */
    public static function debug($message, array $context = null) {
        return self::_write($message, $context, 'debug');
    }

    /**
     * Sends/Writes an error message to the log
     *
     * @param string $message
     * @param array $context
     * @return \Phalcon\Logger\AdapterInterface
     */
    public static function error($message, array $context = null) {
        return self::_write($message, $context, 'error');
    }

    /**
     * Sends/Writes an info message to the log
     *
     * @param string $message
     * @param array $context
     * @return \Phalcon\Logger\AdapterInterface
     */
    public static function info($message, array $context = null) {
        return self::_write($message, $context, 'info');
    }

    /**
     * Sends/Writes a notice message to the log
     *
     * @param string $message
     * @param array $context
     * @return \Phalcon\Logger\AdapterInterface
     */
    public static function notice($message, array $context = null) {
        return self::_write($message, $context, 'notice');
    }

    /**
     * Sends/Writes a warning message to the log
     *
     * @param string $message
     * @param array $context
     * @return \Phalcon\Logger\AdapterInterface
     */
    public static function warning($message, array $context = null) {
        return self::_write($message, $context, 'warning');
    }

    /**
     * Sends/Writes an alert message to the log
     *
     * @param string $message
     * @param array $context
     * @return \Phalcon\Logger\AdapterInterface
     */
    public static function alert($message, array $context = null) {
        return self::_write($message, $context, 'alert');
    }

    /**
     * Sends/Writes message in any type to the log
     *
     * @param mixed $message
     * @param array $context
     * @param mixed $type
     * @return \Phalcon\Logger\AdapterInterface
     */
    public static function log($message = null, array $context = null, $type = 'debug') {
        return self::_write($message, $context, $type);
    }

    /**
     * Logs messages to the internal logger. Appends logs to the logger
     *
     * @param mixed $message
     * @param array $context
     * @param mixed $type
     * @return \Phalcon\Logger\AdapterInterface
     */
    private static function _write($message, $context, $type)
    {
        if($logger = di('logger')) {
            return $logger->$type($message, $context);
        }
    }

}
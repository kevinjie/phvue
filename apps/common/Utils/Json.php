<?php

namespace Phvue\Common\Utils;
use Phalcon\Logger\Formatter;

/**
 * Phalcon\Logger\Formatter\Json
 *
 * Formats messages using JSON encoding
 */
class Json extends Formatter
{

    /**
     * Applies a format to a message before sent it to the internal log
     *
     * @param string message
     * @param int type
     * @param int timestamp
     * @param array $context
     * @return string
     */
    public function format($message, $type, $timestamp, $context = null)
    {
        if (is_array($context)) {
            $message = $this->interpolate($message, $context);
        }

        return json_encode([
            "type" => $this->getTypeString($type),
            "message" => is_array($message) ? json_encode($message, JSON_UNESCAPED_UNICODE) : $message,
            "time" => date("Y-m-d H:i:s"),
            "timestamp" => $timestamp
        ], JSON_UNESCAPED_UNICODE) . PHP_EOL;
    }
}
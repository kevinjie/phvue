<?php

namespace Phvue\Common\Models;

use Phvue\Common\Utils\Logger;
use Phalcon\Db\RawValue;
use Phalcon\Mvc\Model;

class Base extends Model
{
    private static $_errorMsg;
    private static $_errorCode;
    private static $_internalError;
    private static $_isUnderTransaction = false;

    protected $_tableName = '';

    public function initialize()
    {
        $this->useDynamicUpdate(true);
    }

    public static function getAddConditions()
    {
        return [];
    }

    public function getSource()
    {
        return $this->_tableName;
    }

    public static function setErrorMsg($msg,$internal=false)
    {
        if($internal){
            self::$_internalError = $msg;
            Logger::error($msg);
        }else{
            self::$_errorMsg = $msg;
        }
    }

    public static function getErrorMsg($internal=false)
    {
        if($internal){
            return self::$_internalError;
        }else{
            return self::$_errorMsg;
        }
    }

    public static function setErrorCode($code)
    {
        self::$_errorCode = $code;
    }

    public static function clearError(){
        self::$_errorMsg = null;
        self::$_errorCode = null;
        self::$_internalError = null;
    }

    public static function isUnderTransaction($set = null)
    {
        if (!is_null($set)) {
            self::$_isUnderTransaction = $set;
        } else {
            return self::$_isUnderTransaction;
        }
    }

    public static function getErrorCode()
    {
        return self::$_errorCode;
    }

    public function save($data = null, $whiteList = null)
    {
        $error_messages = '';
        $t = null;

        if (isset($this->update_time)) {
            $this->update_time = new RawValue("NOW()");
        }
        try {
            $ret = Parent::save($data, $whiteList);

        } catch (\Exception $e) {
            $ret = false;
            $error_messages = $e->getMessage();
            $error_messages = $error_messages . $e->getTraceAsString();
            $t = $e;
        }

        if (!$ret) {
            foreach ($this->getMessages() as $message) {
                $error_messages = $error_messages . '(' . $message . ')';
            }
        }

        return $ret;
    }

    public function update($data = null, $whiteList = null)
    {
        $error_messages = '';
        $t = null;

        if (isset($this->update_time)) {
            $this->update_time = new RawValue("NOW()");
        }
        try {
            $ret = Parent::update($data, $whiteList);

        } catch (\Exception $e) {
            $ret = false;
            $error_messages = $e->getMessage();
            $error_messages = $error_messages . $e->getTraceAsString();
            $t = $e;
        }

        if (!$ret) {
            foreach ($this->getMessages() as $message) {
                $error_messages = $error_messages . '(' . $message . ')';
            }
        }

        return $ret;
    }

    public static function find($parameters = null)
    {
        return parent::find(self::addCondition($parameters));
    }

    public static function findFirst($parameters = null)
    {
        return parent::findFirst(self::addCondition($parameters));
    }

    public static function findFirstById($parameters = null)
    {
        return parent::findFirstById(self::addCondition($parameters));
    }

    public static function count($parameters = null)
    {
        return parent::count(self::addCondition($parameters));
    }

    public static function sum($parameters = null)
    {
        return parent::sum(self::addCondition($parameters));
    }

    public static function addCondition($parameters)
    {
        $addConditions = array_filter(static::getAddConditions());
        if (empty($addConditions)) {
            return $parameters;
        }

        $addConditions = implode(' AND ', $addConditions);

        if (is_array($parameters)) {
            if (isset($parameters[0])) {
                $conditions = &$parameters[0];
            } elseif (isset($parameters["conditions"])) {
                $conditions = &$parameters["conditions"];
            } else {
                $parameters["conditions"] = '';
                $conditions = &$parameters["conditions"];
            }
        } elseif (is_numeric($parameters)) {
            return $parameters;
        } else {
            $conditions = &$parameters;
        }

        if (is_array($conditions)) {
            $conditions[] = [$addConditions];
        } else {
            if (empty($conditions)) {
                $conditions = $addConditions;
            } else {
                $nonConditionKeywords = [
                    ' order by ',
                    ' limit ',
                    ' group by ',
                ];
                $firstPosition = false;
                foreach ($nonConditionKeywords as $keyword) {
                    $position = strpos(strtolower($conditions), $keyword);
                    if ($position !== false && ($firstPosition === false || $firstPosition > $position)) {
                        $firstPosition = $position;
                    }
                }
                if ($firstPosition !== false) {
                    $conditions = "(" . ((substr($conditions, 0, $firstPosition)) ?: " 1 != 1 ") . ") AND $addConditions " . substr($conditions, $firstPosition);
                } else {
                    $conditions = "($conditions) AND $addConditions";
                }
            }
        }

        return $parameters;
    }
}
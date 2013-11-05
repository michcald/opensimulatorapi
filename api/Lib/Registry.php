<?php

abstract class Lib_Registry
{
    private static $registry = array();
    
    public static function set($key, $value)
    {
	return self::$registry[$key] = $value;
    }
    
    public static function get($key)
    {
	return self::$registry[$key];
    }
}
<?php

abstract class Model_OpenSim
{
    // generation a random uuid with this pattern 75f1b6f0-ec96-46b9-93d4-e9becce499c0
    public static function getRandomId()
    {
        $chunks = array();
        $chunks[] = self::getRandomHexString(8);
        $chunks[] = self::getRandomHexString(4);
        $chunks[] = self::getRandomHexString(4);
        $chunks[] = self::getRandomHexString(4);
        $chunks[] = self::getRandomHexString(12);
        
        return implode('-', $chunks);
    }
    
    private static function getRandomHexString($length)
    {
        return substr(str_shuffle('0123456789abcdef'), 0, $length);
    }
}
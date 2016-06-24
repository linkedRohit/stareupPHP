<?php

namespace ElasticClient;

use Exception;
use SimpleXMLElement;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CommonUtil
 *
 * @author prabin
 */
class CommonUtil
{

    public static function isEmpty($obj) {
        if (is_null($obj)) {
            return true;
        }
        return empty($obj);
    }
    
    public static function getRandomString($length = 16, $prefix = '') {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $prefix . $str;
    }

    public static function getArrayValueSafe($array, $key, $default = null) {
        if (empty($array)) {
            return $default;
        }
        if (array_key_exists($key, $array)) {
            return $array[$key];
        } else {
            return $default;
        }
    }

    public static function getTimeStringAsHour($str) {
        $num = self::getTimeStringAsMinutes($str);
        return round($num / 60);
    }

    public static function getTimeStringAsMinutes($str) {
        $num = self::extractFirstFloatFromString($str, 0);
        $param = substr($str, -1);
        switch ($param) {
            case "h":
                return $num * 60;
            case "d":
                return $num * 24 * 60;
            case "w":
                return $num * 24 * 7 * 60;
            case "M":
                return $num * 24 * 30 * 60;

            default:
                return $num;
        }
    }

    public static function extractFirstFloatFromString($str, $default = null) {
        preg_match('!\d+(?:\.\d+)?!', $str, $matches);
        if ($matches) {
            return $matches[0];
        }
        return $default;
    }

    public static function getIntervalText($minutes) {
        if ($minutes >= 60 * 24 * 30) {
            return (round($minutes / (60 * 24 * 30))) . " month";
        } elseif ($minutes >= 60 * 24 * 7) {
            return (round($minutes / (60 * 24 * 7))) . " week";
        } elseif ($minutes >= 60 * 24) {
            $num = (round($minutes / (60 * 24)));
            return $num . (($num > 1) ? " days" : " day");
        } elseif ($minutes >= 60) {
            $num = (round($minutes / 60));
            return $num . (($num > 1) ? " hours" : " hour");
        } else {
            return $minutes . (($minutes > 1) ? " minutes" : " minute");
        }
    }

    public static function getDateStrAsFormat($dateStr, $inputFormat, $outputFormat) {
        $dtime = date_create_from_format($inputFormat, $dateStr);
        return date($outputFormat, $dtime->getTimestamp());
    }

    public static function addToDateStrAsFormat($dateStr, $addSeconds, $inputFormat, $outputFormat = "") {
        $dtime = date_create_from_format($inputFormat, $dateStr);
        if (empty($outputFormat)) {
            $outputFormat = $inputFormat;
        }
        return date($outputFormat, $dtime->getTimestamp() + $addSeconds);
    }

    public static function startsWith($haystack, $needle) {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }

    public static function isDateInformat($dateStr, $inputFormat) {
        try {
            $dtime = date_create_from_format($inputFormat, $dateStr);
            if ($dtime) {
                return true;
            }
        } catch (Exception $exc) {
            
        }
        return false;
    }

    public static function combine1DArray($arr1, $arr2) {
        if (self::isEmpty($arr1)) {
            return $arr2;
        } elseif (self::isEmpty($arr2)) {
            return $arr1;
        }
        if (!is_array($arr1)) {
            $arr1 = array($arr1);
        }
        if (!is_array($arr2)) {
            $arr2 = array($arr2);
        }
        foreach ($arr2 as $value) {
            $arr1[] = $value;
        }
        return $arr1;
    }


    public static function arrayToXml($array, $root="root") {
        $xml = new SimpleXMLElement("<$root></$root>");
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subnode = $xml->addChild("$key");
                    array_to_xml($value, $subnode);
                } else {
                    $subnode = $xml->addChild("item$key");
                    array_to_xml($value, $subnode);
                }
            } else {
                $xml->addChild("$key", htmlspecialchars("$value"));
            }
        }
        $dom = dom_import_simplexml($xml);
        return $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);
    }

    public static function getMappedType($type) {
        if (preg_match('/application\/xml/', $type, $matches)) {
            return 'xml';
        } elseif (preg_match('/text\/xml/', $type, $matches)) {
            return 'xml';
        } elseif (preg_match('/application\/json/', $type, $matches)) {
            return 'json';
        } elseif (preg_match('/application\/x-www-form-urlencode/', $type, $matches)) {
            return 'form';
        } else {
            return $type;
        }
    }
}
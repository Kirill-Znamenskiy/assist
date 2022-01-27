<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */
namespace KZ\Assist;

class Cast {


    static public function to_array($value, $is_strict_mode = false) : array {
        if (is_array($value)) return $value;
        if (!isset($value)) return [];
        if (empty($is_strict_mode)) {
            if (empty($value)) return [];
        }
        return [$value];
    }
    static public function toArray($value, $is_strict_mode = false) { return static::to_array($value, $is_strict_mode); }



    static public function to_string($value) : string {
        return (string)$value;
    }
    static public function toString($value) { return static::to_string($value); }



    static public function to_int($value, $is_strict_mode = true) : int {
        if (is_integer($value)) return $value;
        if (!is_string($value)) $value = strval($value);
        if ($is_strict_mode) {
            $value = ltrim($value,'0');
            if (strval(intval($value)) !== $value) {
                throw new \RuntimeException();
            }
        }
        return (int)$value;
    }
    static public function toInt($value, $is_strict_mode = true) { return static::to_int($value, $is_strict_mode); }


    static public function to_bool($value) : bool {
        return (bool)$value;
    }
    static public function toBool($value) { return static::to_bool($value); }


}
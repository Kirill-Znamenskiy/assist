<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */
namespace KZ\Assist;

class Take {

    static public function extract_value($arr, $value_name) {
        if (empty($arr)) $arr = [];
        if (!isset($value_name)) $value_names = [];
        elseif (is_array($value_name)) $value_names = $value_name;
        else $value_names = [$value_name];

        foreach ($value_names AS $value_name) {
            if (isset($arr[$value_name])) return $arr[$value_name];
        }
        return null;
    }


    static public function as_is($value, $if_empty_value = null, array $options = null, $value_name = null) {
        $start_value = $value;

        if (true
            AND empty($value)
            AND empty($options['is_ignore_if_empty_value'])
        ) {
            $value = $if_empty_value;
        }

        if (isset($options['is_allow_null']) AND empty($options['is_allow_null'])) {
            if (!isset($value)) throw static::exc($value, 'Value {value} is null!', $start_value, $value_name);
        }
        if (isset($options['is_allow_empty']) AND empty($options['is_allow_empty'])) {
            if (empty($value)) throw static::exc($value, 'Value {value} is empty!', $start_value, $value_name);
        }

        return $value;
    }
    static public function as_is2($arr, $value_name, $if_empty_value = null, array $options = null) {
        return static::as_is(static::extract_value($arr, $value_name), $if_empty_value, $options, $value_name);
    }



    static public function simple_as_int_or_null($value, $if_empty_value = null) : ?int {
        $start_value = $value;
        $value = static::as_is($value, $if_empty_value);
        if (empty($value)) $value = $if_empty_value;
        if (!isset($value) AND !isset($if_empty_value)) return $value;
        if (!is_integer($value)) throw static::exc($value, 'Value {value} is not integer!', $start_value);
        return $value;
    }
    static public function simple_as_int($value, $if_empty_value = 0) : int {
        if (!isset($if_empty_value)) $if_empty_value = 0;
        $value = static::simple_as_int_or_null($value, $if_empty_value);
        if (!isset($value)) throw new \LogicException();
        return $value;
    }
    static public function as_int_or_null($value, $if_empty_value = null, array $options = null) : ?int {
        $start_value = $value;
        if (!isset($if_empty_value)) {
            if (!isset($options['is_allow_null'])) $options['is_allow_null'] = true;
        }
        if ($if_empty_value === false) {
            if (!isset($options['is_allow_null'])) $options['is_allow_null'] = false;
            if (!isset($options['is_allow_empty'])) $options['is_allow_empty'] = false;
        }
        $value = static::as_is($value, $if_empty_value, $options);
        if (true
            AND !isset($value)
            AND !isset($if_empty_value)
            AND !empty($options['is_allow_null'])
        ) {
            return null;
        }
        if (!empty($options['is_with_cast'])) $value = Cast::to_int($value);
        if (!is_integer($value)) throw static::exc($value, 'Value {value} is not integer!', $start_value);
        return $value;
    }
    static public function as_int_or_null2($arr, $value_name, $if_empty_value = null, array $options = null) : ?int {
        return static::as_int_or_null(static::extract_value($arr, $value_name), $if_empty_value, $options);
    }
    static public function as_int($value, $if_empty_value = 0, array $options = null) : int {
        if (!isset($if_empty_value)) $if_empty_value = 0;
        $options['is_allow_null'] = false;
        $value = static::as_int_or_null($value, $if_empty_value, $options);
        if (!isset($value)) throw new \LogicException();
        return $value;
    }
    static public function as_int2($arr, $value_name, $if_empty_value = 0, array $options = null) : int {
        return static::as_int(static::extract_value($arr, $value_name), $if_empty_value, $options);
    }



    static public function as_int_positive($value, $if_empty_value = 0, array $options = null) : int {
        $start_value = $value;
        $value = static::as_int($value, $if_empty_value, $options);
        $is_ok = ($value > 0);
        if (empty($is_ok)) {
            throw static::exc($value, 'Value {value} non positive!', $start_value);
        }
        return $value;
    }
    static public function as_int_positive2($arr, $value_name, $if_empty_value = 0, array $options = null) : int {
        return static::as_int_positive(static::extract_value($arr, $value_name), $if_empty_value, $options);
    }

    static public function as_int_positive_or_zero($value, $if_empty_value = 0, array $options = null) : int {
        $start_value = $value;
        $value = static::as_int($value, $if_empty_value, $options);
        $is_ok = ($value >= 0);
        if (empty($is_ok)) throw static::exc($value, 'Value {value} negative!', $start_value);
        return $value;
    }
    static public function as_int_positive_or_zero2($arr, $value_name, $if_empty_value = 0, array $options = null) : int {
        return static::as_int_positive_or_zero(static::extract_value($arr, $value_name), $if_empty_value, $options);
    }


    static public function simple_as_array_or_null($value, $if_empty_value = null) : ?array {
        $start_value = $value;
        $value = static::as_is($value, $if_empty_value);
        if (empty($value)) $value = $if_empty_value;
        if (!isset($value) AND !isset($if_empty_value)) return $value;
        if (!is_array($value)) throw static::exc($value, 'Value {value} is not array!', $start_value);
        return $value;
    }
    static public function simple_as_array($value, $if_empty_value = []) : array {
        if (!isset($if_empty_value)) $if_empty_value = [];
        $value = static::simple_as_array_or_null($value, $if_empty_value);
        if (!isset($value)) throw new \LogicException();
        return $value;
    }
    static public function as_array_or_null($value, $if_empty_value = null, array $options = null) : ?array {
        $start_value = $value;
        if (!isset($if_empty_value)) {
            if (!isset($options['is_allow_null'])) $options['is_allow_null'] = true;
        }
        if ($if_empty_value === false) {
            if (!isset($options['is_allow_null'])) $options['is_allow_null'] = false;
            if (!isset($options['is_allow_empty'])) $options['is_allow_empty'] = false;
        }
        $value = static::as_is($value, $if_empty_value, $options);
        if (true
            AND !isset($value)
            AND !isset($if_empty_value)
            AND !empty($options['is_allow_null'])
        ) {
            return null;
        }
        if (!empty($options['is_with_cast'])) $value = Cast::to_array($value, false);
        if (!is_array($value)) throw static::exc($value, 'Value {value} is not array!', $start_value);
        return $value;
    }
    static public function as_array_or_null2($arr, $value_name, $if_empty_value = null, array $options = null) : ?array {
        return static::as_array_or_null(static::extract_value($arr, $value_name), $if_empty_value, $options);
    }
    static public function as_array($value, $if_empty_value = [], array $options = null) : array {
        if (!isset($if_empty_value)) $if_empty_value = [];
        $options['is_allow_null'] = false;
        $value = static::as_array_or_null($value, $if_empty_value, $options);
        if (!isset($value)) throw new \LogicException();
        return $value;
    }
    static public function as_array2($arr, $value_name, $if_empty_value = [], array $options = null) : array {
        return static::as_array(static::extract_value($arr, $value_name), $if_empty_value, $options);
    }






    static public function simple_as_string_or_null($value, $if_empty_value = null) : ?string {
        $start_value = $value;
        $value = static::as_is($value, $if_empty_value);
        if (empty($value)) $value = $if_empty_value;
        if (!isset($value) AND !isset($if_empty_value)) return $value;
        if (!is_string($value)) throw static::exc($value, 'Value {value} is not string!', $start_value);
        return $value;
    }
    static public function simple_as_string($value, $if_empty_value = '') : string {
        if (!isset($if_empty_value)) $if_empty_value = [];
        $value = static::simple_as_string_or_null($value, $if_empty_value);
        if (!isset($value)) throw new \LogicException();
        return $value;
    }
    static public function as_string_or_null($value, $if_empty_value = null, array $options = null) : ?string {
        $start_value = $value;
        if (!isset($if_empty_value)) {
            if (!isset($options['is_allow_null'])) $options['is_allow_null'] = true;
        }
        if ($if_empty_value === false) {
            if (!isset($options['is_allow_null'])) $options['is_allow_null'] = false;
            if (!isset($options['is_allow_empty'])) $options['is_allow_empty'] = false;
        }
        $value = static::as_is($value, $if_empty_value, $options);
        if (true
            AND !isset($value)
            AND !isset($if_empty_value)
            AND !empty($options['is_allow_null'])
        ) {
            return null;
        }
        if (!empty($options['is_with_cast'])) $value = Cast::to_array($value);
        if (!is_string($value)) throw static::exc($value, 'Value {value} is not string!', $start_value);
        return $value;
    }
    static public function as_string_or_null2($arr, $value_name, $if_empty_value = null, array $options = null) : ?string {
        return static::as_string_or_null(static::extract_value($arr, $value_name), $if_empty_value, $options);
    }
    static public function as_string($value, $if_empty_value = '', array $options = null) : string {
        if (!isset($if_empty_value)) $if_empty_value = '';
        $options['is_allow_null'] = false;
        $value = static::as_string_or_null($value, $if_empty_value, $options);
        if (!isset($value)) throw new \LogicException();
        return $value;
    }
    static public function as_string2($arr, $value_name, $if_empty_value = '', array $options = null) : string {
        return static::as_string(static::extract_value($arr, $value_name), $if_empty_value, $options);
    }




    static public function simple_as_bool_or_null($value, $if_empty_value = null) : ?bool {
        $start_value = $value;
        $value = static::as_is($value, $if_empty_value);
        if (empty($value)) $value = $if_empty_value;
        if (!isset($value) AND !isset($if_empty_value)) return $value;
        if (!is_bool($value)) throw static::exc($value, 'Value {value} is not bool!', $start_value);
        return $value;
    }
    static public function simple_as_bool($value, $if_empty_value = false) : bool {
        if (!isset($if_empty_value)) $if_empty_value = false;
        $value = static::simple_as_bool_or_null($value, $if_empty_value);
        if (!isset($value)) throw new \LogicException();
        return $value;
    }
    static public function as_bool_or_null($value, $if_empty_value = null, array $options = null) : ?bool {
        $start_value = $value;
        if (!isset($if_empty_value)) {
            if (!isset($options['is_allow_null'])) $options['is_allow_null'] = true;
        }
        if ($if_empty_value === '') {
            if (!isset($options['is_allow_null'])) $options['is_allow_null'] = false;
            if (!isset($options['is_allow_empty'])) $options['is_allow_empty'] = false;
        }
        $value = static::as_is($value, $if_empty_value, $options);
        if (true
            AND !isset($value)
            AND !isset($if_empty_value)
            AND !empty($options['is_allow_null'])
        ) {
            return null;
        }
        if (!empty($options['is_with_cast'])) $value = Cast::to_array($value);
        if (!is_bool($value)) throw static::exc($value, 'Value {value} is not bool!', $start_value);
        return $value;
    }
    static public function as_bool_or_null2($arr, $value_name, $if_empty_value = null, array $options = null) : ?bool {
        return static::as_bool_or_null(static::extract_value($arr, $value_name), $if_empty_value, $options);
    }
    static public function as_bool($value, $if_empty_value = false, array $options = null) : bool {
        if (!isset($if_empty_value)) $if_empty_value = false;
        $options['is_allow_null'] = false;
        $value = static::as_bool_or_null($value, $if_empty_value, $options);
        if (!isset($value)) throw new \LogicException();
        return $value;
    }
    static public function as_bool2($arr, $value_name, $if_empty_value = false, array $options = null) : bool {
        return static::as_bool(static::extract_value($arr, $value_name), $if_empty_value, $options);
    }




    static public function as_object_or_null($value, $if_empty_value = null, $base_class_name = null, array $options = null) : ?object {
        $start_value = $value;
        if (!isset($if_empty_value)) {
            if (!isset($options['is_allow_null'])) $options['is_allow_null'] = true;
        }
        if ($if_empty_value === false) {
            if (!isset($options['is_allow_null'])) $options['is_allow_null'] = false;
            if (!isset($options['is_allow_empty'])) $options['is_allow_empty'] = false;
        }
        $value = static::as_is($value, $if_empty_value, $options);
        if (true
            AND !isset($value)
            AND !isset($if_empty_value)
            AND !isset($base_class_name)
            AND !empty($options['is_allow_null'])
        ) {
            return null;
        }
        if (!empty($options['is_with_cast'])) $value = Cast::to_array($value);
        if (!is_object($value)) throw static::exc($value, 'Value {value} is not object!', $start_value);
        if (isset($base_class_name)) {
            if (!($value instanceof $base_class_name)) throw static::exc($value, 'Value {value} is not instance of '.$base_class_name.'!', $start_value);
        }
        return $value;
    }
    static public function as_object_or_null2($arr, $value_name, $if_empty_value = null, array $options = null) : ?object {
        return static::as_object_or_null(static::extract_value($arr, $value_name), $if_empty_value, $options);
    }
    static public function as_object($value, $if_empty_value = \stdClass::class, $base_class_name = null, array $options = null) : object {
        if (!isset($if_empty_value)) $if_empty_value = \stdClass::class;
        $options['is_allow_null'] = false;
        $value = static::as_object_or_null($value, $if_empty_value, $base_class_name, $options);
        if (!isset($value)) throw new \LogicException();
        return $value;
    }
    static public function as_object2($arr, $value_name, $if_empty_value = \stdClass::class, array $options = null) : object {
        return static::as_object(static::extract_value($arr, $value_name), $if_empty_value, $options);
    }






    static public function exc($value, $template, $start_value, string $value_name = null) : \InvalidArgumentException {

        $value = var_export($value,true);

        if (!empty($value_name)) {
            $value = $value_name.'='.$value;
        }

        $start_value = var_export($start_value,true);

        $value = $start_value.' => '.$value;

        $template = str_replace('{value}', $value, $template);
        return new \InvalidArgumentException($template);
    }



}
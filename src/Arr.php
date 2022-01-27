<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */
namespace KZ\Assist;

use KZ\Assist\Traits\ArrHierarchyTrait;

class Arr extends \Illuminate\Support\Arr {

    use ArrHierarchyTrait;

    /**
     * @inheritDoc
     * @param  string|array|null  $key
     */
    static public function set(&$array, $key, $value) {
        if (is_array($key)) $key = implode('.',$key);
        return parent::set($array, $key, $value);
    }
    /**
     * @inheritDoc
     * @param  string|int|array|null  $key
     */
    static public function get($array, $key, $default = null) {
        if (is_array($key)) $key = implode('.',$key);
        return parent::get($array, $key, $default);
    }

    /**
     * Append value to then end of array
     * @param  array $array
     * @param  mixed $value
     */
    static public function append($array, $value) {
        array_push($array, $value);
        return $array;
    }

    static public function merge($arr1, $arr2, bool $is_recursive = false, bool $is_merge_int = true) {
        $arr1 = Cast::to_array($arr1, true);
        $arr2 = Cast::to_array($arr2, true);

        $ret = [];
        $extra_v2s = [];
        foreach ($arr1 AS $kkk => $v1) {
            if (!isset($arr2[$kkk])) {
                $ret[$kkk] = $v1;
                continue;
            }
            $v2 = $arr2[$kkk];
            unset($arr2[$kkk]);

            if (!$is_merge_int AND is_integer($kkk)) {
                $ret[$kkk] = $v1;
                $extra_v2s[] = $v2;
                continue;
            }

            if ($is_recursive AND is_array($v1) AND is_array($v2)) {
                $v2 = static::merge($v1, $v2, $is_merge_int, $is_recursive);
            }

            $ret[$kkk] = $v2;
        }
        foreach ($arr2 AS $kkk => $v2) {
            $ret[$kkk] = $v2;
        }
        foreach ($extra_v2s AS $v2) {
            $ret[] = $v2;
        }
        return $ret;
    }

    static public function merge_recursive($arr1, $arr2, bool $is_recursive = true, bool $is_merge_int = true) {
        return static::merge($arr1, $arr2, $is_recursive, $is_merge_int);
    }


    static public function union($arr1, $arr2, $is_with_unique = false) : array {
        $arr1 = Cast::to_array($arr1, true);
        $arr2 = Cast::to_array($arr2, true);

        $ret = array_merge(array_values($arr1), array_values($arr2));
        if ($is_with_unique) $ret = array_unique($ret);
        return $ret;
    }


    static public function combine($keys, $values) {
        $keys = Arr::wrap($keys);
        if (!is_array($values)) return array_fill_keys($keys, $values);
        else return array_combine($keys, $values);
    }


}
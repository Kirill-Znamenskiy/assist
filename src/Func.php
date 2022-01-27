<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */
namespace KZ\Assist;

use KZ\Assist\Exceptions\FuncInvalidArgumentException;

class Func {

    /**
     * @param \Traversable|array $collection
     * @param callable          $callback
     * @param mixed             $userdata  [optional]
     * @return array
     */
    static public function filter($collection, $callback, $userdata = null) {

        FuncInvalidArgumentException::assert_collection($collection, __FUNCTION__, 1);
        FuncInvalidArgumentException::assert_callback($callback, __FUNCTION__, 2);

        $ret = [];
        foreach ($collection as $index => $value) {
            $res = call_user_func_array($callback, [$index, $value, $userdata, $collection]);
            if (($res !== false) AND ($res !== null)) $ret[$index] = $value;
        }
        return $ret;
    }

    /**
     * @param \Traversable|array $collection
     * @param mixed             $check_value
     * @param mixed             $userdata  [optional]
     * @return array
     */
    static public function filter_by_value($collection, $check_value, $userdata = null) {
        return static::filter($collection, function($index, $value) use ($check_value) { return ($value === $check_value); }, $userdata);
    }


    /**
     * @param \Traversable|array $collection
     * @param callable          $callback
     * @param mixed             $userdata  [optional]
     * @return array
     */
    static public function map($collection, $callback, $userdata = null) {

        FuncInvalidArgumentException::assert_collection($collection, __FUNCTION__, 1);
        FuncInvalidArgumentException::assert_callback($callback, __FUNCTION__, 2);

        $ret = [];
        foreach ($collection as $index => $value) {

            $aux = call_user_func_array($callback, [$index, $value, $userdata, $collection]);

            if (true
                AND is_array($aux)
                AND isset($aux['_OVERRIDE_AND_INDEX_AND_VALUE_'],$aux['new_index'],$aux['new_value'])
                AND ($aux['_OVERRIDE_AND_INDEX_AND_VALUE_'] === true)
            ) {
                $new_index = $aux['new_index'];
                $new_value = $aux['new_value'];
            }
            elseif (true
                AND is_array($aux)
                AND isset($aux['_UNSET_THIS_INDEX_'])
                AND ($aux['_UNSET_THIS_INDEX_'] === true)
            ) {
                continue;
            }
            else {
                $new_index = $index;
                $new_value = $aux;
            }

            $ret[$new_index] = $new_value;

        }
        return $ret;
    }


    /**
     * @param \Traversable|array $collection
     * @param callable          $callback
     * @param mixed             $userdata  [optional]
     * @return array
     */
    static public function map_first_value($collection, $callback, $userdata = null) {

        FuncInvalidArgumentException::assert_collection($collection, __FUNCTION__, 1);
        FuncInvalidArgumentException::assert_callback($callback, __FUNCTION__, 2);

        $ret = [];
        foreach ($collection as $index => $value) {

            $aux = call_user_func_array($callback, [$value, $index, $userdata, $collection]);

            if (true
                AND is_array($aux)
                AND isset($aux['_OVERRIDE_AND_INDEX_AND_VALUE_'],$aux['new_index'],$aux['new_value'])
                AND ($aux['_OVERRIDE_AND_INDEX_AND_VALUE_'] === true)
            ) {
                $new_index = $aux['new_index'];
                $new_value = $aux['new_value'];
            }
            elseif (true
                AND is_array($aux)
                AND isset($aux['_UNSET_THIS_INDEX_'])
                AND ($aux['_UNSET_THIS_INDEX_'] === true)
            ) {
                continue;
            }
            else {
                $new_index = $index;
                $new_value = $aux;
            }

            $ret[$new_index] = $new_value;

        }
        return $ret;
    }


    /**
     * Recursive map
     *
     * @param \Traversable|array $collection
     * @param callable          $callback
     * @param mixed             $userdata  [optional]
     * @return array
     */
    static public function recmap($collection, $callback, $userdata = null, $visited_indexes = []) {

        FuncInvalidArgumentException::assert_collection($collection, __FUNCTION__, 1);
        FuncInvalidArgumentException::assert_callback($callback, __FUNCTION__, 2);

        $ret = [];
        foreach ($collection as $index => $value) {

            if (is_array($value)) $value = static::recmap($value, $callback, $userdata, array_merge($visited_indexes, [$index]));

            $aux = call_user_func_array($callback, [$index, $value, $userdata, $collection, $visited_indexes]);

            if (true
                AND is_array($aux)
                AND isset($aux['_OVERRIDE_INDEX_AND_VALUE_'],$aux['new_index'],$aux['new_value'])
                AND ($aux['_OVERRIDE_INDEX_AND_VALUE_'] === true)
            ) {
                $new_index = $aux['new_index'];
                $new_value = $aux['new_value'];
            }
            else {
                $new_index = $index;
                $new_value = $aux;
            }

            $ret[$new_index] = $new_value;

        }
        return $ret;
    }



    /**
     * Reduce
     *
     * @param \Traversable|array $collection
     * @param callable          $callback
     * @param mixed             $initial  [optional]
     * @param mixed             $userdata  [optional]
     * @return mixed
     */
    static public function reduce($collection, $callback, $initial = null, $userdata = null) {
        return static::lreduce($collection, $callback, $initial, $userdata);
    }


    /**
     * Left Reduce
     *
     * @param \Traversable|array $collection
     * @param callable          $callback
     * @param mixed             $initial  [optional]
     * @param mixed             $userdata  [optional]
     * @return mixed
     */
    static public function lreduce($collection, $callback, $initial = null, $userdata = null) {

        FuncInvalidArgumentException::assert_collection($collection, __FUNCTION__, 1);
        FuncInvalidArgumentException::assert_callback($callback, __FUNCTION__, 2);

        if (empty($collection)) return $initial;
        if (!isset($initial)) $initial = array_shift($collection);
        if (empty($collection)) return $initial;

        $accum = $initial;
        foreach ($collection as $index => $value) {
            $accum = call_user_func_array($callback, [$index, $value, $accum, $userdata, $collection]);
        }
        return $accum;
    }


    /**
     * Right Reduce
     *
     * @param \Traversable|array $collection
     * @param callable          $callback
     * @param mixed             $initial  [optional]
     * @param mixed             $userdata  [optional]
     * @return mixed
     */
    static public function rreduce($collection, $callback, $initial = null, $userdata = null) {

        FuncInvalidArgumentException::assert_collection($collection, __FUNCTION__, 1);
        FuncInvalidArgumentException::assert_callback($callback, __FUNCTION__, 2);

        if (empty($collection)) return $initial;
        if (!isset($initial)) $initial = array_pop($collection);
        if (empty($collection)) return $initial;

        $collection = array_reverse($collection, true);

        $ret = $initial;
        foreach ($collection as $index => $value) {
            $ret = call_user_func_array($callback, [$index, $value, $ret, $userdata, $collection]);
        }
        return $ret;
    }

    /**
     * Fold (=reduce)
     *
     * @param \Traversable|array $collection
     * @param callable          $callback
     * @param mixed             $initial  [optional]
     * @param mixed             $userdata  [optional]
     * @return mixed
     */
    static public function fold($collection, $callback, $initial = null, $userdata = null) {
        return static::lreduce($collection, $callback, $initial, $userdata);
    }
    static public function lfold($collection, $callback, $initial = null, $userdata = null) {
        return static::lreduce($collection, $callback, $initial, $userdata);
    }
    static public function rfold($collection, $callback, $initial = null, $userdata = null) {
        return static::rreduce($collection, $callback, $initial, $userdata);
    }


}

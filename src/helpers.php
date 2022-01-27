<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */

if (! function_exists('kzcollect')) {
    /**
     * Create a kz collection from the given value.
     *
     * @param  mixed  $value
     * @return \KZ\Assist\Collection
     */
    function kzcollect($value = null) {
        if ($value instanceof \KZ\Assist\Collection) return $value;
        return (new \KZ\Assist\Collection($value));
    }
}
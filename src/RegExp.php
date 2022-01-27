<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */
namespace KZ\Assist;

class RegExp {

    /**
     * Extract preg matches from string
     *
     * @param  string  $string
     * @param  string  $regexp
     * @return array|false
     */
    public static function extract_preg_matches($string, $regexp) {
        $matches = [];
        if (preg_match($regexp, $string, $matches)) {
            return $matches;
        }
        return false;

    }


}
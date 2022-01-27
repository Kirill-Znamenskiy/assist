<?php declare(strict_types=1);
namespace Tests\Unit;

require_once(__DIR__.'/../../src/Arr.php');

use KZ\Assist\Arr;
use KZ\Assist\Take;
use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase {


    public function test_merge() {


        $results = [
            [ [[1 => 'a', 3 => 'b'], [1 => 'c']], [1 => 'c', 3 => 'b']],
        ];

        $this->check_results([Arr::class, 'merge'], $results);
        //$this->check_results([static::class, 'arr_merge'], $results);
    }

    public function test_union() {


        $results = [
            [ [[1 => 'a', 3 => 'b'], [1 => 'c']], ['a','b','c']],
        ];

        $this->check_results([Arr::class, 'union'], $results);
        //$this->check_results([static::class, 'arr_merge'], $results);
    }

    static public function arr_merge($arr1, $arr2) {
        return array_merge($arr1, $arr2);
    }


    public function check_results($callback, $results) {
        foreach ($results AS $result) {
            $args = $result[0];
            $expected_result = $result[1];
            try {
                $res = call_user_func_array($callback, $args);
                $this->assertTrue(($expected_result === $res), var_export(['$args' => $args, '$expected_result' => $expected_result, '$res' => $res],true));
            }
            catch (\InvalidArgumentException $e) {
                $this->assertTrue(($expected_result === get_class($e)), var_export(['$args' => $args, '$expected_result' => $expected_result, '$res' => get_class($e).': '.$e->getMessage()],true));
            }
        }
    }

}
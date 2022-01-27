<?php declare(strict_types=1);
namespace Tests\Unit;

require_once(__DIR__.'/../../src/Take.php');


use KZ\Assist\Take;
use PHPUnit\Framework\TestCase;

class TakeTest extends TestCase {

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

    public function test_value() {


        $results = [

            /* null */ [[ null       ], null  ],
            /* empty */ [[ false     ], false ],
            /* empty */ [[ 0         ], 0     ],
            /* empty */ [[ 0.0       ], 0.0   ],
            /* empty */ [[ ''        ], ''    ],
            /* empty */ [[ '0'       ], '0'   ],
            /* empty */ [[ []        ], []    ],
            /* non-empty */ [[ true  ], true  ],
            /* non-empty */ [[ 1     ], 1     ],
            /* non-empty */ [[ 0.5   ], 0.5   ],
            /* non-empty */ [[ '0.0' ], '0.0' ],
            /* non-empty */ [[ [333] ], [333] ],

            [[ ['opts' => ['k' => 5], 'name' => 'k'] ], 5],

            [[ [['k' => 5], 'k'] ], 5   ],
            [[ [['k' => 5], 'z'] ], null],
            [[ [null, 'z']       ], null],
            [[ ['b', 'z' ]       ], ['b','z']],

            [[ [['a','b'], ['z'] ]       ], ['b','z']],
        ];

        $this->check_results([Take::class, 'value'], $results);
    }

    public function test_as_is() {


        $results = [

            /* null */ [[ null       ], null ],
            /* empty */ [[ false     ], null ],
            /* empty */ [[ 0         ], null ],
            /* empty */ [[ 0.0       ], null ],
            /* empty */ [[ ''        ], null ],
            /* empty */ [[ '0'       ], null ],
            /* empty */ [[ []        ], null ],
            /* non-empty */ [[ true  ], true  ],
            /* non-empty */ [[ 1     ], 1     ],
            /* non-empty */ [[ 0.5   ], 0.5   ],
            /* non-empty */ [[ '0.0' ], '0.0' ],
            /* non-empty */ [[ [333] ], [333] ],


            /* null */ [[ null       , null ], null ],
            /* empty */ [[ false     , null ], null ],
            /* empty */ [[ 0         , null ], null ],
            /* empty */ [[ 0.0       , null ], null ],
            /* empty */ [[ ''        , null ], null ],
            /* empty */ [[ '0'       , null ], null ],
            /* empty */ [[ []        , null ], null ],
            /* non-empty */ [[ true  , null ], true  ],
            /* non-empty */ [[ 1     , null ], 1     ],
            /* non-empty */ [[ 0.5   , null ], 0.5   ],
            /* non-empty */ [[ '0.0' , null ], '0.0' ],
            /* non-empty */ [[ [333] , null ], [333] ],


            /* null */ [[ null       , 0], 0],
            /* empty */ [[ false     , 0], 0],
            /* empty */ [[ 0         , 0], 0],
            /* empty */ [[ 0.0       , 0], 0],
            /* empty */ [[ ''        , 0], 0],
            /* empty */ [[ '0'       , 0], 0],
            /* empty */ [[ []        , 0], 0],
            /* non-empty */ [[ true  , 0], true  ],
            /* non-empty */ [[ 1     , 0], 1     ],
            /* non-empty */ [[ 0.5   , 0], 0.5   ],
            /* non-empty */ [[ '0.0' , 0], '0.0' ],
            /* non-empty */ [[ [333] , 0], [333] ],

            /* null */ [[ null       , false ], false ],
            /* empty */ [[ false     , false ], false ],
            /* empty */ [[ 0         , false ], false ],
            /* empty */ [[ 0.0       , false ], false ],
            /* empty */ [[ ''        , false ], false ],
            /* empty */ [[ '0'       , false ], false ],
            /* empty */ [[ []        , false ], false ],
            /* non-empty */ [[ true  , false ], true  ],
            /* non-empty */ [[ 1     , false ], 1     ],
            /* non-empty */ [[ 0.5   , false ], 0.5   ],
            /* non-empty */ [[ '0.0' , false ], '0.0' ],
            /* non-empty */ [[ [333] , false ], [333] ],


            /* null */ [[ null       , true ], true ],
            /* empty */ [[ false     , true ], true ],
            /* empty */ [[ 0         , true ], true ],
            /* empty */ [[ 0.0       , true ], true ],
            /* empty */ [[ ''        , true ], true ],
            /* empty */ [[ '0'       , true ], true ],
            /* empty */ [[ []        , true ], true ],
            /* non-empty */ [[ true  , true ], true  ],
            /* non-empty */ [[ 1     , true ], 1     ],
            /* non-empty */ [[ 0.5   , true ], 0.5   ],
            /* non-empty */ [[ '0.0' , true ], '0.0' ],
            /* non-empty */ [[ [333] , true ], [333] ],

        ];

        $this->check_results([Take::class, 'as_is'], $results);
    }

    public function test_as_int_or_null() {


        $results = [

            /* null */ [[ null       ], null],
            /* empty */ [[ false     ], null],
            /* empty */ [[ 0         ], null],
            /* empty */ [[ 0.0       ], null],
            /* empty */ [[ ''        ], null],
            /* empty */ [[ '0'       ], null],
            /* empty */ [[ []        ], null],
            /* non-empty */ [[ true  ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     ], 1],
            /* non-empty */ [[ 0.5   ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' ], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] ], \InvalidArgumentException::class],


            /* null */ [[ null       , null ], null],
            /* empty */ [[ false     , null ], null],
            /* empty */ [[ 0         , null ], null],
            /* empty */ [[ 0.0       , null ], null],
            /* empty */ [[ ''        , null ], null],
            /* empty */ [[ '0'       , null ], null],
            /* empty */ [[ []        , null ], null],
            /* non-empty */ [[ true  , null ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , null ], 1],
            /* non-empty */ [[ 0.5   , null ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , null ], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , null ], \InvalidArgumentException::class],


            /* null */ [[ null       , 0], 0],
            /* empty */ [[ false     , 0], 0],
            /* empty */ [[ 0         , 0], 0],
            /* empty */ [[ 0.0       , 0], 0],
            /* empty */ [[ ''        , 0], 0],
            /* empty */ [[ '0'       , 0], 0],
            /* empty */ [[ []        , 0], 0],
            /* non-empty */ [[ true  , 0], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , 0], 1],
            /* non-empty */ [[ 0.5   , 0], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , 0], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , 0], \InvalidArgumentException::class],


            /* null */ [[ null       , false], \InvalidArgumentException::class],
            /* empty */ [[ false     , false], \InvalidArgumentException::class],
            /* empty */ [[ 0         , false], \InvalidArgumentException::class],
            /* empty */ [[ 0.0       , false], \InvalidArgumentException::class],
            /* empty */ [[ ''        , false], \InvalidArgumentException::class],
            /* empty */ [[ '0'       , false], \InvalidArgumentException::class],
            /* empty */ [[ []        , false], \InvalidArgumentException::class],
            /* non-empty */ [[ true  , false], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , false], 1],
            /* non-empty */ [[ 0.5   , false], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , false], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , false], \InvalidArgumentException::class],


            /* null */ [[ null       , ''], \InvalidArgumentException::class],
            /* empty */ [[ false     , ''], \InvalidArgumentException::class],
            /* empty */ [[ 0         , ''], \InvalidArgumentException::class],
            /* empty */ [[ 0.0       , ''], \InvalidArgumentException::class],
            /* empty */ [[ ''        , ''], \InvalidArgumentException::class],
            /* empty */ [[ '0'       , ''], \InvalidArgumentException::class],
            /* empty */ [[ []        , ''], \InvalidArgumentException::class],
            /* non-empty */ [[ true  , ''], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , ''], 1],
            /* non-empty */ [[ 0.5   , ''], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , ''], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , ''], \InvalidArgumentException::class],


            /* null */ [[ null       , 3], 3],
            /* empty */ [[ false     , 3], 3],
            /* empty */ [[ 0         , 3], 3],
            /* empty */ [[ 0.0       , 3], 3],
            /* empty */ [[ ''        , 3], 3],
            /* empty */ [[ '0'       , 3], 3],
            /* empty */ [[ []        , 3], 3],
            /* non-empty */ [[ true  , 3], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , 3], 1],
            /* non-empty */ [[ 0.5   , 3], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , 3], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , 3], \InvalidArgumentException::class],
        ];

        $this->check_results([Take::class, 'as_int_or_null'], $results);
        $this->check_results([Take::class, 'simple_as_int_or_null'], $results);
    }

    public function test_as_int_positive() {


        $results = [

            /* null */ [[ null       ], \InvalidArgumentException::class],
            /* empty */ [[ false     ], \InvalidArgumentException::class],
            /* empty */ [[ 0         ], \InvalidArgumentException::class],
            /* empty */ [[ 0.0       ], \InvalidArgumentException::class],
            /* empty */ [[ ''        ], \InvalidArgumentException::class],
            /* empty */ [[ '0'       ], \InvalidArgumentException::class],
            /* empty */ [[ []        ], \InvalidArgumentException::class],
            /* non-empty */ [[ true  ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     ], 1],
            /* non-empty */ [[ 0.5   ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' ], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] ], \InvalidArgumentException::class],

            /* non-empty */ [[ -3    ], \InvalidArgumentException::class],
            /* non-empty */ [[ -2    ], \InvalidArgumentException::class],
            /* non-empty */ [[ -1    ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0     ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     ], 1],
            /* non-empty */ [[ 2     ], 2],
            /* non-empty */ [[ 3     ], 3],
        ];

        $this->check_results([Take::class, 'as_int_positive'], $results);
    }

    public function test_as_int_positive_or_zero() {


        $results = [

            /* null */ [[ null       ], 0],
            /* empty */ [[ false     ], 0],
            /* empty */ [[ 0         ], 0],
            /* empty */ [[ 0.0       ], 0],
            /* empty */ [[ ''        ], 0],
            /* empty */ [[ '0'       ], 0],
            /* empty */ [[ []        ], 0],
            /* non-empty */ [[ true  ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     ], 1],
            /* non-empty */ [[ 0.5   ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' ], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] ], \InvalidArgumentException::class],

            /* non-empty */ [[ -3    ], \InvalidArgumentException::class],
            /* non-empty */ [[ -2    ], \InvalidArgumentException::class],
            /* non-empty */ [[ -1    ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0     ], 0],
            /* non-empty */ [[ 1     ], 1],
            /* non-empty */ [[ 2     ], 2],
            /* non-empty */ [[ 3     ], 3],
        ];

        $this->check_results([Take::class, 'as_int_positive_or_zero'], $results);
    }

    public function test_as_array_or_null() {


        $results = [

            /* null */ [[ null       ], null ],
            /* empty */ [[ false     ], null ],
            /* empty */ [[ 0         ], null ],
            /* empty */ [[ 0.0       ], null ],
            /* empty */ [[ ''        ], null ],
            /* empty */ [[ '0'       ], null ],
            /* empty */ [[ []        ], null ],
            /* non-empty */ [[ true  ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' ], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] ], [333] ],


            /* null */ [[ null       , null ], null],
            /* empty */ [[ false     , null ], null],
            /* empty */ [[ 0         , null ], null],
            /* empty */ [[ 0.0       , null ], null],
            /* empty */ [[ ''        , null ], null],
            /* empty */ [[ '0'       , null ], null],
            /* empty */ [[ []        , null ], null],
            /* non-empty */ [[ true  , null ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , null ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , null ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , null ], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , null ], [333] ],


            /* null */ [[ null       , [] ], [] ],
            /* empty */ [[ false     , [] ], [] ],
            /* empty */ [[ 0         , [] ], [] ],
            /* empty */ [[ 0.0       , [] ], [] ],
            /* empty */ [[ ''        , [] ], [] ],
            /* empty */ [[ '0'       , [] ], [] ],
            /* empty */ [[ []        , [] ], [] ],
            /* non-empty */ [[ true  , [] ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , [] ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , [] ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , [] ], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , [] ], [333] ],


            /* null */ [[ null       , false], \InvalidArgumentException::class],
            /* empty */ [[ false     , false], \InvalidArgumentException::class],
            /* empty */ [[ 0         , false], \InvalidArgumentException::class],
            /* empty */ [[ 0.0       , false], \InvalidArgumentException::class],
            /* empty */ [[ ''        , false], \InvalidArgumentException::class],
            /* empty */ [[ '0'       , false], \InvalidArgumentException::class],
            /* empty */ [[ []        , false], \InvalidArgumentException::class],
            /* non-empty */ [[ true  , false], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , false], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , false], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , false], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , false], [333] ],


            /* null */ [[ null       , ''], \InvalidArgumentException::class],
            /* empty */ [[ false     , ''], \InvalidArgumentException::class],
            /* empty */ [[ 0         , ''], \InvalidArgumentException::class],
            /* empty */ [[ 0.0       , ''], \InvalidArgumentException::class],
            /* empty */ [[ ''        , ''], \InvalidArgumentException::class],
            /* empty */ [[ '0'       , ''], \InvalidArgumentException::class],
            /* empty */ [[ []        , ''], \InvalidArgumentException::class],
            /* non-empty */ [[ true  , ''], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , ''], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , ''], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , ''], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , ''], [333] ],


            /* null */ [[ null       , ['a'] ], ['a'] ],
            /* empty */ [[ false     , ['a'] ], ['a'] ],
            /* empty */ [[ 0         , ['a'] ], ['a'] ],
            /* empty */ [[ 0.0       , ['a'] ], ['a'] ],
            /* empty */ [[ ''        , ['a'] ], ['a'] ],
            /* empty */ [[ '0'       , ['a'] ], ['a'] ],
            /* empty */ [[ []        , ['a'] ], ['a'] ],
            /* non-empty */ [[ true  , ['a'] ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , ['a'] ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , ['a'] ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , ['a'] ], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , ['a'] ], [333] ],
        ];


        $this->check_results([Take::class, 'as_array_or_null'], $results);
        $this->check_results([Take::class, 'simple_as_array_or_null'], $results);
    }

    public function test_as_string_or_null() {


        $results = [

            /* null */ [[ null       ], null ],
            /* empty */ [[ false     ], null ],
            /* empty */ [[ 0         ], null ],
            /* empty */ [[ 0.0       ], null ],
            /* empty */ [[ ''        ], null ],
            /* empty */ [[ '0'       ], null ],
            /* empty */ [[ []        ], null ],
            /* non-empty */ [[ true  ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' ], '0.0'],
            /* non-empty */ [[ [333] ], \InvalidArgumentException::class],


            /* null */ [[ null       , null ], null],
            /* empty */ [[ false     , null ], null],
            /* empty */ [[ 0         , null ], null],
            /* empty */ [[ 0.0       , null ], null],
            /* empty */ [[ ''        , null ], null],
            /* empty */ [[ '0'       , null ], null],
            /* empty */ [[ []        , null ], null],
            /* non-empty */ [[ true  , null ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , null ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , null ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , null ], '0.0'],
            /* non-empty */ [[ [333] , null ], \InvalidArgumentException::class],


            /* null */ [[ null       , '' ], '' ],
            /* empty */ [[ false     , '' ], '' ],
            /* empty */ [[ 0         , '' ], '' ],
            /* empty */ [[ 0.0       , '' ], '' ],
            /* empty */ [[ ''        , '' ], '' ],
            /* empty */ [[ '0'       , '' ], '' ],
            /* empty */ [[ []        , '' ], '' ],
            /* non-empty */ [[ true  , '' ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , '' ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , '' ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , '' ], '0.0'],
            /* non-empty */ [[ [333] , '' ], \InvalidArgumentException::class],


            /* null */ [[ null       , false], \InvalidArgumentException::class],
            /* empty */ [[ false     , false], \InvalidArgumentException::class],
            /* empty */ [[ 0         , false], \InvalidArgumentException::class],
            /* empty */ [[ 0.0       , false], \InvalidArgumentException::class],
            /* empty */ [[ ''        , false], \InvalidArgumentException::class],
            /* empty */ [[ '0'       , false], \InvalidArgumentException::class],
            /* empty */ [[ []        , false], \InvalidArgumentException::class],
            /* non-empty */ [[ true  , false], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , false], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , false], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , false], '0.0'],
            /* non-empty */ [[ [333] , false], \InvalidArgumentException::class],


            /* null */ [[ null       , 333 ], \InvalidArgumentException::class],
            /* empty */ [[ false     , 333 ], \InvalidArgumentException::class],
            /* empty */ [[ 0         , 333 ], \InvalidArgumentException::class],
            /* empty */ [[ 0.0       , 333 ], \InvalidArgumentException::class],
            /* empty */ [[ ''        , 333 ], \InvalidArgumentException::class],
            /* empty */ [[ '0'       , 333 ], \InvalidArgumentException::class],
            /* empty */ [[ []        , 333 ], \InvalidArgumentException::class],
            /* non-empty */ [[ true  , 333 ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , 333 ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , 333 ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , 333 ], '0.0'],
            /* non-empty */ [[ [333] , 333 ], \InvalidArgumentException::class],


            /* null */ [[ null       , 'abc' ], 'abc' ],
            /* empty */ [[ false     , 'abc' ], 'abc' ],
            /* empty */ [[ 0         , 'abc' ], 'abc' ],
            /* empty */ [[ 0.0       , 'abc' ], 'abc' ],
            /* empty */ [[ ''        , 'abc' ], 'abc' ],
            /* empty */ [[ '0'       , 'abc' ], 'abc' ],
            /* empty */ [[ []        , 'abc' ], 'abc' ],
            /* non-empty */ [[ true  , 'abc' ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , 'abc' ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , 'abc' ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , 'abc' ], '0.0'],
            /* non-empty */ [[ [333] , 'abc' ], \InvalidArgumentException::class],
        ];


        $this->check_results([Take::class, 'as_string_or_null'], $results);
        $this->check_results([Take::class, 'simple_as_string_or_null'], $results);
    }

    public function test_as_bool_or_null() {


        $results = [

            /* null */ [[ null       ], null ],
            /* empty */ [[ false     ], null ],
            /* empty */ [[ 0         ], null ],
            /* empty */ [[ 0.0       ], null ],
            /* empty */ [[ ''        ], null ],
            /* empty */ [[ '0'       ], null ],
            /* empty */ [[ []        ], null ],
            /* non-empty */ [[ true  ], true],
            /* non-empty */ [[ 1     ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' ], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] ], \InvalidArgumentException::class],


            /* null */ [[ null       , null ], null],
            /* empty */ [[ false     , null ], null],
            /* empty */ [[ 0         , null ], null],
            /* empty */ [[ 0.0       , null ], null],
            /* empty */ [[ ''        , null ], null],
            /* empty */ [[ '0'       , null ], null],
            /* empty */ [[ []        , null ], null],
            /* non-empty */ [[ true  , null ], true],
            /* non-empty */ [[ 1     , null ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , null ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , null ], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , null ], \InvalidArgumentException::class],


            /* null */ [[ null       , false], false],
            /* empty */ [[ false     , false], false],
            /* empty */ [[ 0         , false], false],
            /* empty */ [[ 0.0       , false], false],
            /* empty */ [[ ''        , false], false],
            /* empty */ [[ '0'       , false], false],
            /* empty */ [[ []        , false], false],
            /* non-empty */ [[ true  , false], true],
            /* non-empty */ [[ 1     , false], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , false], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , false], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , false], \InvalidArgumentException::class],

            /* null */ [[ null       , '' ], \InvalidArgumentException::class],
            /* empty */ [[ false     , '' ], \InvalidArgumentException::class],
            /* empty */ [[ 0         , '' ], \InvalidArgumentException::class],
            /* empty */ [[ 0.0       , '' ], \InvalidArgumentException::class],
            /* empty */ [[ ''        , '' ], \InvalidArgumentException::class],
            /* empty */ [[ '0'       , '' ], \InvalidArgumentException::class],
            /* empty */ [[ []        , '' ], \InvalidArgumentException::class],
            /* non-empty */ [[ true  , '' ], true],
            /* non-empty */ [[ 1     , '' ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , '' ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , '' ], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , '' ], \InvalidArgumentException::class],


            /* null */ [[ null       , 333 ], \InvalidArgumentException::class],
            /* empty */ [[ false     , 333 ], \InvalidArgumentException::class],
            /* empty */ [[ 0         , 333 ], \InvalidArgumentException::class],
            /* empty */ [[ 0.0       , 333 ], \InvalidArgumentException::class],
            /* empty */ [[ ''        , 333 ], \InvalidArgumentException::class],
            /* empty */ [[ '0'       , 333 ], \InvalidArgumentException::class],
            /* empty */ [[ []        , 333 ], \InvalidArgumentException::class],
            /* non-empty */ [[ true  , 333 ], true],
            /* non-empty */ [[ 1     , 333 ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , 333 ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , 333 ], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , 333 ], \InvalidArgumentException::class],


            /* null */ [[ null       , true ], true ],
            /* empty */ [[ false     , true ], true ],
            /* empty */ [[ 0         , true ], true ],
            /* empty */ [[ 0.0       , true ], true ],
            /* empty */ [[ ''        , true ], true ],
            /* empty */ [[ '0'       , true ], true ],
            /* empty */ [[ []        , true ], true ],
            /* non-empty */ [[ true  , true ], true ],
            /* non-empty */ [[ 1     , true ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , true ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , true ], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , true ], \InvalidArgumentException::class],
        ];


        $this->check_results([Take::class, 'as_bool_or_null'], $results);
        $this->check_results([Take::class, 'simple_as_bool_or_null'], $results);
    }


    public function test_as_array() {


        $results = [

            /* null */ [[ null       ], [] ],
            /* empty */ [[ false     ], [] ],
            /* empty */ [[ 0         ], [] ],
            /* empty */ [[ 0.0       ], [] ],
            /* empty */ [[ ''        ], [] ],
            /* empty */ [[ '0'       ], [] ],
            /* empty */ [[ []        ], [] ],
            /* non-empty */ [[ true  ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' ], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] ], [333] ],


            /* null */ [[ null       , null ], [] ],
            /* empty */ [[ false     , null ], [] ],
            /* empty */ [[ 0         , null ], [] ],
            /* empty */ [[ 0.0       , null ], [] ],
            /* empty */ [[ ''        , null ], [] ],
            /* empty */ [[ '0'       , null ], [] ],
            /* empty */ [[ []        , null ], [] ],
            /* non-empty */ [[ true  , null ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , null ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , null ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , null ], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , null ], [333] ],


            /* null */ [[ null       , [] ], [] ],
            /* empty */ [[ false     , [] ], [] ],
            /* empty */ [[ 0         , [] ], [] ],
            /* empty */ [[ 0.0       , [] ], [] ],
            /* empty */ [[ ''        , [] ], [] ],
            /* empty */ [[ '0'       , [] ], [] ],
            /* empty */ [[ []        , [] ], [] ],
            /* non-empty */ [[ true  , [] ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , [] ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , [] ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , [] ], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , [] ], [333] ],


            /* null */ [[ null       , false], \InvalidArgumentException::class],
            /* empty */ [[ false     , false], \InvalidArgumentException::class],
            /* empty */ [[ 0         , false], \InvalidArgumentException::class],
            /* empty */ [[ 0.0       , false], \InvalidArgumentException::class],
            /* empty */ [[ ''        , false], \InvalidArgumentException::class],
            /* empty */ [[ '0'       , false], \InvalidArgumentException::class],
            /* empty */ [[ []        , false], \InvalidArgumentException::class],
            /* non-empty */ [[ true  , false], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , false], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , false], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , false], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , false], [333] ],


            /* null */ [[ null       , ''], \InvalidArgumentException::class],
            /* empty */ [[ false     , ''], \InvalidArgumentException::class],
            /* empty */ [[ 0         , ''], \InvalidArgumentException::class],
            /* empty */ [[ 0.0       , ''], \InvalidArgumentException::class],
            /* empty */ [[ ''        , ''], \InvalidArgumentException::class],
            /* empty */ [[ '0'       , ''], \InvalidArgumentException::class],
            /* empty */ [[ []        , ''], \InvalidArgumentException::class],
            /* non-empty */ [[ true  , ''], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , ''], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , ''], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , ''], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , ''], [333] ],


            /* null */ [[ null       , ['a'] ], ['a'] ],
            /* empty */ [[ false     , ['a'] ], ['a'] ],
            /* empty */ [[ 0         , ['a'] ], ['a'] ],
            /* empty */ [[ 0.0       , ['a'] ], ['a'] ],
            /* empty */ [[ ''        , ['a'] ], ['a'] ],
            /* empty */ [[ '0'       , ['a'] ], ['a'] ],
            /* empty */ [[ []        , ['a'] ], ['a'] ],
            /* non-empty */ [[ true  , ['a'] ], \InvalidArgumentException::class],
            /* non-empty */ [[ 1     , ['a'] ], \InvalidArgumentException::class],
            /* non-empty */ [[ 0.5   , ['a'] ], \InvalidArgumentException::class],
            /* non-empty */ [[ '0.0' , ['a'] ], \InvalidArgumentException::class],
            /* non-empty */ [[ [333] , ['a'] ], [333] ],


            /* null */ [[ null       , null, ['is_with_cast' => true] ], [] ],
            /* empty */ [[ false     , null, ['is_with_cast' => true] ], [] ],
            /* empty */ [[ 0         , null, ['is_with_cast' => true] ], [] ],
            /* empty */ [[ 0.0       , null, ['is_with_cast' => true] ], [] ],
            /* empty */ [[ ''        , null, ['is_with_cast' => true] ], [] ],
            /* empty */ [[ '0'       , null, ['is_with_cast' => true] ], [] ],
            /* empty */ [[ []        , null, ['is_with_cast' => true] ], [] ],
            /* non-empty */ [[ true  , null, ['is_with_cast' => true] ], [true]  ],
            /* non-empty */ [[ 1     , null, ['is_with_cast' => true] ], [1]     ],
            /* non-empty */ [[ 0.5   , null, ['is_with_cast' => true] ], [0.5]   ],
            /* non-empty */ [[ '0.0' , null, ['is_with_cast' => true] ], ['0.0'] ],
            /* non-empty */ [[ [333] , null, ['is_with_cast' => true] ], [333]   ],


        ];


        $this->check_results([Take::class, 'as_array'], $results);
    }


}
<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */
namespace KZ\Assist\Traits;

use KZ\Assist\Apply;

trait CreateTrait {

    public function __construct() { }
    static public function create($config = []) {
        return Apply::config_to_object((new static()), $config);
    }
    static public function new($config = []) {
        return Apply::config_to_object((new static()), $config);
    }

}

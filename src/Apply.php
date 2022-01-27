<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */
namespace KZ\Assist;


class Apply {

    static public function config_to_object($object, $config) : object {
        if (is_array($object) AND is_object($config)) {
            $aux = $object;
            $object = $config;
            $config = $aux;
        }
        if (!is_object($object)) throw new \InvalidArgumentException();
        if (!is_array($config)) throw new \InvalidArgumentException();
        foreach ($config AS $kkk => $vvv) {
            if (!is_string($kkk)) continue;
            $pref = substr($kkk,0,1);
            if ($pref === '&') {
                $kkk = substr($kkk,1);
                if (!($vvv instanceof \Closure)) throw new \InvalidArgumentException();
                $object->$kkk = call_user_func($vvv, $object->$kkk, $object);
                continue;
            }
            if ($pref === '@') {
                $kkk = substr($kkk,1);
                if (!method_exists($object, $kkk)) throw new \InvalidArgumentException();
                if (!is_array($vvv)) $vvv = [$vvv];
                call_user_func_array([$object,$kkk],$vvv);
                continue;
            }
            $object->$kkk = $vvv;
        }
        return $object;
    }

}
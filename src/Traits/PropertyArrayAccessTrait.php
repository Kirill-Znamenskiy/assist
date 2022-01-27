<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */
namespace KZ\Assist\Traits;

trait PropertyArrayAccessTrait {

    /** @inheritDoc */
    public function offsetSet($offset, $value) { return $this->__set($offset, $value); }

    /** @inheritDoc */
    public function offsetGet($offset) { return $this->__get($offset); }

    /** @inheritDoc */
    public function offsetExists($offset) { return $this->__isset($offset); }

    /** @inheritDoc */
    public function offsetUnset($offset) { return $this->__unset($offset); }

}

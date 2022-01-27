<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */
namespace KZ\Assist\Traits;

/**
 * @mixin \KZ\Assist\Collection
 */
trait CollectionGetSetTrait {

    /** @inheritDoc */
    public function get($key, $default = null) {
        if (is_array($key)) {
            $ret = $this->items;
            foreach ($key AS $k) {
                if (array_key_exists($k, $ret)) {
                    $ret = $ret[$k];
                }
                else {
                    return value($default);
                }
            }
            return $ret;
        }
        return parent::get($key, $default);
    }

    /** @inheritDoc */
    public function put($key, $value) {
        if (is_array($key)) {
            $items = &$this->items;
            foreach ($key AS $k) {
                if (!array_key_exists($k, $items)) $items[$k] = [];
                $items = &$items[$k];
            }
            $items = $value;
            return $this;
        }
        return parent::put($key, $value);
    }

    /**
     * Alias for the "put" method.
     */
    public function set($key, $value) {
        return $this->put($key, $value);
    }


}

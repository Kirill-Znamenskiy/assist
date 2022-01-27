<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */
namespace KZ\Assist\Traits;

use Illuminate\Support\Arr;
use KZ\Illuminate\Support\Exceptions\SecondSetSituationException;

/**
 * @mixin \KZ\Assist\Collection
 */
trait CollectionIncrementTrait {


    /**
     * Increment an item in the collection by key, even if it doesn't exist.
     *
     * @param  string  $key
     * @param  integer $inc_by_value
     * @return static
     */
    public function inc($key, $inc_by_value = 1) {
        $value = static::get($key,0);
        $value = intval($value) + intval($inc_by_value);
        return static::put($key,$value);
    }

    /**
     * Alias for the "inc" method.
     */
    public function increment($key, $value = null) {
        return $this->inc($key, $value);
    }

    /**
     * inc and get
     *
     * @param  string  $key
     * @param  integer $inc_by_value
     * @return integer
     */
    public function inc_and_get($key, $inc_by_value = 1) {
        return $this->inc($key,$inc_by_value)->get($key);
    }


}

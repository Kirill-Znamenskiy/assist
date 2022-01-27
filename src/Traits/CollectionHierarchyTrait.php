<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */
namespace KZ\Assist\Traits;

use KZ\Assist\Arr;
use KZ\Assist\Exceptions\CollectionSecondSetSituationException;

/**
 * @mixin \KZ\Assist\Collection
 */
trait CollectionHierarchyTrait {

    public function hier($hier_by = null, $columns = null, array $options = null) {
        $ret = Arr::hier($this->items, $hier_by, $columns, $options);
        return new static($ret);
    }



    /**
     * Alias for the "hier" method.
     */
    public function hierarchy($hier_by = null, $columns = null, array $options = null) {
        return $this->hier($hier_by, $columns, $options);
    }

}

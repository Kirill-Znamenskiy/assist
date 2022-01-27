<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */
namespace KZ\Assist;


class Collection extends \Illuminate\Support\Collection {
    use \KZ\Assist\Traits\CollectionHierarchyTrait;
    use \KZ\Assist\Traits\CollectionIncrementTrait;
    use \KZ\Assist\Traits\CollectionGetSetTrait;
}
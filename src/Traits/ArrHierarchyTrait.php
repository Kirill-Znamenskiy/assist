<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */
namespace KZ\Assist\Traits;

use KZ\Assist\Arr;
use KZ\Assist\Exceptions\CollectionSecondSetSituationException;

/**
 * @mixin \KZ\Assist\Arr
 */
trait ArrHierarchyTrait {


    /**
     *
     *
     *
     * $ret = Arr::hier($posts,['author_id', 'id']);
     * <===>
     * $ret = []; foreach($posts->all() AS $ind => $post) { $ret[$post['author_id']][$post['id']] = $post; }
     *
     *
     * $ret = Arr::hier($posts,['lang_id', 'author_id', 'id'], ['id', 'author_id'])->all();
     * <===>
     * $ret = []; foreach($posts->all() AS $ind => $post) { $ret[$post['lang_id']][$post['author_id']][$post['id']] = $post; }
     *
     *
     * $ret = Arr::hier($posts,['author_id','_new_index_']);
     * <===>
     * $ret = Arr::hier($posts,['author_id','_index_']);
     * <===>
     * $ret = Arr::hier($posts,['author_id','']);
     * <===>
     * $ret = []; foreach($posts->all() AS $ind => $post) { $ret[$post['author_id']][] = $post; }
     *
     *
     * $ret = Arr::hier($posts,['author_id','_old_index_']);
     * <===>
     * $ret = Arr::hier($posts,['author_id','_row_index_']);
     * <===>
     * $ret = Arr::hier($posts,['author_id','_old_row_index_']);
     * <===>
     * $ret = []; foreach($posts->all() AS $ind => $post) { $ret[$post['author_id']][$ind] = $post; }
     *
     *
     * $ret = Arr::hier($posts,['author_id', 'id'], 'id');
     * <===>
     * $ret = []; foreach($posts->all() AS $ind => $post) { $ret[$post['author_id']][$post['id']] = $post['id']; }
     *
     *
     * $ret = Arr::hier($posts,['author_id', 'id'], ['id', 'author_id']);
     * <===>
     * $ret = []; foreach($posts->all() AS $ind => $post) { $ret[$post['author_id']][$post['id']] = ['id' => $post['id'], 'author_id' => $post['author_id']]; }
     *
     *
     *
     *
     * @param mixed $array
     * @param mixed $hier_by
     * @param mixed $columns
     * @param array $options
     *
     * @return static
     *
     * @throws CollectionSecondSetSituationException
     * @throws \UnexpectedValueException
     */
    static public function hier($array, $hier_by = null, $columns = null, array $options = null) : array {
        $is_stop_second_set_situation = Arr::get($options, 'is_stop_second_set_situation', true);

        if (empty($hier_by)) $hier_by = ['_new_index_'];
        if (!is_array($hier_by)) $hier_by = [$hier_by];

        $ret = [];
        foreach ($array AS $index => $item) {
            $rt = &$ret;
            $last_isset = false;
            $hier_by_values = [];
            foreach ($hier_by AS $hier_by_elem) {
                if (empty($hier_by_elem) OR in_array($hier_by_elem, ['_new_index_','_index_'], true)) {
                    $hier_by_value = count($rt);
                }
                elseif (in_array($hier_by_elem, ['_old_index_','_row_index_','_old_row_index_'], true)) {
                    $hier_by_value = $index;
                }
                elseif ($hier_by_elem instanceof \Closure) {
                    $hier_by_value = call_user_func_array($hier_by_elem, [$item, $index]);
                }
                elseif (true
                    AND is_string($hier_by_elem)
                    AND (substr($hier_by_elem,0,1) === '@')
                    AND is_object($item)
                    AND method_exists($item, substr($hier_by_elem,1))
                ) {
                    $hier_by_value = call_user_func_array([$item,substr($hier_by_elem,1)],[]);
                }
                else {
                    $hier_by_value = data_get($item,$hier_by_elem);
                }

                if (!isset($hier_by_value)) {
                    throw new \UnexpectedValueException('(!isset($hier_by_value)) '
                        ."\n".' $hier_by='.var_export($hier_by,true)
                        ."\n".' $item='.var_export($item,true)
                        ."\n".' $hier_by_values='.var_export($hier_by_values,true)
                        ."\n".' $hier_by_elem='.var_export($hier_by_elem,true)
                        ."\n".' $hier_by_value='.var_export($hier_by_value,true)
                    );
                }

                if (is_object($hier_by_value)) $hier_by_value = strval($hier_by_value);

                $hier_by_values[] = $hier_by_value;

                $last_isset = isset($rt[$hier_by_value]);
                if (!$last_isset) $rt[$hier_by_value] = [];
                $rt = &$rt[$hier_by_value];
            }

            if ($last_isset AND ($is_stop_second_set_situation === 'fully')) {
                throw new CollectionSecondSetSituationException('Second set situation number one!'
                    ."\n".' $hier_by='.var_export($hier_by,true)
                    ."\n".' $item='.var_export($item,true)
                    ."\n".' $hier_by_values='.var_export($hier_by_values,true)
                    ."\n".' $rt='.var_export($rt,true)
                );
            }

            if (empty($columns)) $value = $item;
            elseif (is_array($columns)) {
                $value = [];
                foreach ($columns as $column_name) {
                    $column_value = data_get($item, $column_name);
                    $value[$column_name] = $column_value;
                }
            }
            elseif ($columns instanceof \Closure) {
                $value = call_user_func($columns, $item);
            }
            else {
                $value = data_get($item, $columns);
            }

            if ($last_isset AND !empty($is_stop_second_set_situation) AND ($value !== $rt)) {
                throw new CollectionSecondSetSituationException('Second set situation number two!'
                    ."\n".' $hier_by='.var_export($hier_by,true)
                    ."\n".' $item='.var_export($item,true)
                    ."\n".' $hier_by_values='.var_export($hier_by_values,true)
                    ."\n".' $rt='.var_export($rt,true)
                    ."\n".' $value='.var_export($value,true)
                );
            }

            $rt = $value;
        }
        return $ret;
    }




    /**
     * Alias for the "hier" method.
     */
    static public function hierarchy($array, $hier_by = null, $columns = null, $options = []) : array {
        return static::hier($array, $hier_by, $columns, $options);
    }

}

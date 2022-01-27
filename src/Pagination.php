<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */
namespace KZ\Assist;


class Pagination {


    static public function normalize_options(array $options) {

        $total_cnt = Take::as_int_positive_or_zero2($options, 'total_cnt');
        $per_page_cnt = Take::as_int_positive2($options, 'per_page_cnt', false);

        $last_page_num = (int)max((int)ceil($total_cnt / $per_page_cnt), 1);

        $first_page_num = Take::as_int_positive2($options, 'first_page_num', 1, ['is_with_cast' => true]);
        $current_page_num = Take::as_int_positive2($options, 'current_page_num', $first_page_num, ['is_with_cast' => true]);
        if ($current_page_num > $last_page_num) $current_page_num = $first_page_num;


        $active_pages_start_num = Take::as_int_positive2($options, 'active_pages_start_num', $current_page_num, ['is_with_cast' => true]);
        if ($active_pages_start_num > $current_page_num) $active_pages_start_num = $current_page_num;
        $active_pages_finish_num = Take::as_int_positive2($options, 'active_pages_finish_num', $current_page_num, ['is_with_cast' => true]);
        if ($active_pages_finish_num < $current_page_num) $active_pages_finish_num = $current_page_num;


        $options['total_cnt'] = $total_cnt;
        $options['per_page_cnt'] = $per_page_cnt;
        $options['first_page_num'] = $first_page_num;
        $options['current_page_num'] = $current_page_num;
        $options['last_page_num'] = $last_page_num;
        $options['active_pages_start_num'] = $active_pages_start_num;
        $options['active_pages_finish_num'] = $active_pages_finish_num;

        return $options;
    }

    static public function make_options($total_cnt, $per_page_cnt, $current_page_num = null, $first_page_num = null, $active_pages_start_num = null, $active_pages_finish_num = null) {
        return static::normalize_options([
            'total_cnt' => $total_cnt,
            'per_page_cnt' => $per_page_cnt,
            'current_page_num' => $current_page_num,
            'first_page_num' => $first_page_num,
            'active_pages_start_num' => $active_pages_start_num,
            'active_pages_finish_num' => $active_pages_finish_num,
        ]);
    }

    static public function make_range_key2kit(array $options) {
        $options = static::normalize_options($options);

        $first_page_num = $options['first_page_num'];
        $last_page_num = $options['last_page_num'];
        $current_page_num = $options['current_page_num'];

        $active_pages_start_num = $options['active_pages_start_num'];
        $active_pages_finish_num = $options['active_pages_finish_num'];


        $cnt_pages_on_any_side = Take::as_int_positive2($options, 'cnt_pages_on_any_side', 3);
        $cnt_pages_on_first_right_side = Take::as_int_positive2($options, 'cnt_pages_on_first_right_side', $cnt_pages_on_any_side);
        $cnt_pages_on_middle_left_side = Take::as_int_positive2($options, 'cnt_pages_on_middle_left_side', $cnt_pages_on_any_side);
        $cnt_pages_on_middle_right_side = Take::as_int_positive2($options, 'cnt_pages_on_middle_right_side', $cnt_pages_on_any_side);
        $cnt_pages_on_last_left_side = Take::as_int_positive2($options, 'cnt_pages_on_last_left_side', $cnt_pages_on_any_side);


        $ret = [
            'first' => [],
            'middle' => [],
            'last' => [],
        ];


        $ret['first']['start_page_num'] = $first_page_num;
        $ret['first']['center_page_num'] = $first_page_num;
        $ret['first']['finish_page_num'] = (int)min($last_page_num, $first_page_num + $cnt_pages_on_first_right_side);


        $ret['last']['start_page_num'] = (int)max($first_page_num, $last_page_num - $cnt_pages_on_last_left_side);
        $ret['last']['center_page_num'] = $last_page_num;
        $ret['last']['finish_page_num'] = $last_page_num;


        $ret['middle']['start_page_num'] = (int)max($first_page_num, $active_pages_start_num - $cnt_pages_on_middle_left_side);
        $ret['middle']['start_page_num'] = (int)min($ret['middle']['start_page_num'], $ret['last']['start_page_num']);
        $ret['middle']['center_page_num'] = $current_page_num;
        $ret['middle']['finish_page_num'] = (int)min($last_page_num, $active_pages_finish_num + $cnt_pages_on_middle_right_side);
        $ret['middle']['finish_page_num'] = (int)max($ret['middle']['finish_page_num'], $ret['first']['finish_page_num']);


        if ($ret['first']['start_page_num'] >= $ret['middle']['start_page_num']) unset($ret['first']);
        elseif ($ret['first']['finish_page_num'] >= $ret['middle']['start_page_num']) $ret['first']['finish_page_num'] = $ret['middle']['start_page_num']-1;
        elseif (($ret['first']['finish_page_num']+2) <= $ret['middle']['start_page_num']) $ret['first']['skip_page_num'] = (int)floor(($ret['first']['finish_page_num']+$ret['middle']['start_page_num'])/2);

        if ($ret['middle']['finish_page_num'] >= $ret['last']['finish_page_num']) unset($ret['last']);
        elseif ($ret['middle']['finish_page_num'] >= $ret['last']['start_page_num']) $ret['last']['start_page_num'] = $ret['middle']['finish_page_num']+1;
        elseif (($ret['middle']['finish_page_num']+2) <= $ret['last']['start_page_num']) $ret['last']['skip_page_num'] = (int)floor(($ret['middle']['finish_page_num']+$ret['last']['start_page_num'])/2);

        return $ret;
    }

    static public function make_to_page_num2kit(array $options) {
        $options = static::normalize_options($options);

        $current_page_num = $options['current_page_num'];

        $active_pages_start_num = $options['active_pages_start_num'];
        $active_pages_finish_num = $options['active_pages_finish_num'];

        $range_key2kit = Take::as_array_or_null2($options, 'range_key2kit');
        if (!isset($range_key2kit)) $range_key2kit = static::make_range_key2kit($options);


        $ret = [];

        $frst_range_kit = $range_key2kit['first'] ?? null;
        if (!empty($frst_range_kit)) {
            for ($to_page_num = $frst_range_kit['start_page_num']; $to_page_num <= $frst_range_kit['finish_page_num']; ++$to_page_num) {
                $ret[$to_page_num] = [
                    'to_page_num' => $to_page_num,
                    'label' => $to_page_num,
                    'is_current' => ($to_page_num === $current_page_num),
                    'is_active' => (($active_pages_start_num <= $to_page_num) AND ($to_page_num <= $active_pages_finish_num)),
                    'range_key' => 'first',
                ];
            }

            if (!empty($frst_range_kit['skip_page_num'])) {
                $to_page_num = $frst_range_kit['skip_page_num'];
                $ret[$to_page_num] = [
                    'to_page_num' => $to_page_num,
                    'label' => '...',
                    'is_current' => ($to_page_num === $current_page_num),
                    'is_active' => (($active_pages_start_num <= $to_page_num) AND ($to_page_num <= $active_pages_finish_num)),
                    'range_key' => 'first',
                    'is_skip' => true,
                ];
            }
        }

        $mddl_range_kit = $range_key2kit['middle'] ?? null;
        if (!empty($mddl_range_kit)) {
            for ($to_page_num = $mddl_range_kit['start_page_num']; $to_page_num <= $mddl_range_kit['finish_page_num']; ++$to_page_num) {
                $ret[$to_page_num] = [
                    'to_page_num' => $to_page_num,
                    'label' => $to_page_num,
                    'is_current' => ($to_page_num === $current_page_num),
                    'is_active' => (($active_pages_start_num <= $to_page_num) AND ($to_page_num <= $active_pages_finish_num)),
                    'range_key' => 'middle',
                ];
            }
        }

        $last_range_kit = $range_key2kit['last'] ?? null;
        if (!empty($last_range_kit)) {
            if (!empty($last_range_kit['skip_page_num'])) {
                $to_page_num = $last_range_kit['skip_page_num'];
                $ret[$to_page_num] = [
                    'to_page_num' => $to_page_num,
                    'label' => '...',
                    'is_current' => ($to_page_num === $current_page_num),
                    'is_active' => (($active_pages_start_num <= $to_page_num) AND ($to_page_num <= $active_pages_finish_num)),
                    'range_key' => 'last',
                    'is_skip' => true
                ];

            }

            for ($to_page_num = $last_range_kit['start_page_num']; $to_page_num <= $last_range_kit['finish_page_num']; ++$to_page_num) {
                $ret[$to_page_num] = [
                    'to_page_num' => $to_page_num,
                    'label' => $to_page_num,
                    'is_current' => ($to_page_num === $current_page_num),
                    'is_active' => (($active_pages_start_num <= $to_page_num) AND ($to_page_num <= $active_pages_finish_num)),
                    'range_key' => 'last',
                ];
            }
        }

        return $ret;
    }
}
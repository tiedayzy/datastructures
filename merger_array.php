<?php
/**
 * 数据结构常见问题：合并两个有序数组的PHP实现
 *
 */
function merger_sort_array($array1,$array2){
    $return = [];
    $index1 = $index2 = 0;
    while ($index1 <= count($array1)) {
        if ($array1[$index1] <= $array2[$index2]) {
            $return[] = $array1[$index1];
            $index1++;
        } else {
            $return[] = $array2[$index2];
            $index2++;
        }
    }
    while ($index1 < count($array1)) {
        $return[] = $array1[$index1];
        $index1++;
    }
    while ($index2 < count($array2)) {
        $return[] = $array2[$index2];
        $index2++;
    }
    return $return;

}
 ?>

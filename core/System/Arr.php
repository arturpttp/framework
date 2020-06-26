<?php


namespace Core\System;


class Arr
{

    public static function merge()
    {
        $arrays = func_get_args();
        $array = [];
        foreach ($arrays as $arr)
            $array = array_merge($arr, $array);
        return $array;
    }

}
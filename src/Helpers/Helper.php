<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace jiny;

if (!function_exists("dbinfo")) {
    function dbinfo($path="../dbinfo.php")
    {       
        return include($path);
    }
}

if (!function_exists("mysql")) {
    function mysql($info=null)
    {    
        return \Jiny\Mysql\Connection::instance($info);
    }
}

if (!function_exists("is_assoArray")) {
    function is_assoArray($arr) : bool
    {
        if (\is_array($arr) && \array_keys($arr) !== range(0, count($arr) - 1)) {
            return true;
        } else {
            return false;
        }
    }
}


function arr_merge($arr1, $arr2)
{
    foreach($arr2 as $v) $arr1 []= $v;
    return $arr1;
}

function arr_keymerge($arr1, $arr2)
{
    foreach($arr2 as $k => $v) $arr1[$k]= $v;
    return $arr1;
}
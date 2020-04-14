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
    function mysqlPDO($info)
    {       
    }
}

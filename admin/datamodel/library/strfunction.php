<?php

/*
  Copyright (C) 2009-2010  Fabio Mattei <burattino@gmail.com>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class StrHelper {

    public static function formatQRY($str, $array_strings, $array_int, $tables) {
        $array_strings = StrHelper::filterArgouments($array_strings);
        $out = StrHelper::replaceStrings($str, $array_strings);
        $out = StrHelper::replaceNumbers($out, $array_int);
        $out = StrHelper::fixTableName($out, $tables);
        return $out;
    }

    public static function filterArgouments($array) {
        $newarray = array();
        foreach ($array as $key) {
            if (get_magic_quotes_gpc ()) {
                $newitem = stripslashes($key);
            } else {
                $newitem = $key;
            }
            $newitem = mysql_real_escape_string($newitem);
            $newarray[] = $newitem;
        }
        return $newarray;
    }

    /**
     * @assert ("hello", "wprld") == "mydada"
     */
    public static function replaceStrings($str, $array) {
        $newArray = explode('@?@', $str);
        $out = $newArray[0];
        $i = 1;
        foreach ($array as $key) {
            $out .= '\'' . $key . '\'' . $newArray[$i];
            $i++;
        }
        return $out;
    }

    public static function replaceNumbers($str, $array) {
        $newArray = explode('@#@', $str);
        $out = $newArray[0];
        $i = 1;
        foreach ($array as $key) {
            if (is_numeric($key)) {
                $out .= ' ' . $key . ' ' . $newArray[$i];
            }
            $i++;
        }
        return $out;
    }

    public static function fixTableName($qry, $tables) {
        $out = $qry;
        foreach ($tables as $oldName => $newName) {
            $out = str_replace(" $oldName ", " $newName ", $out);
        }
        return $out;
    }

}

?>

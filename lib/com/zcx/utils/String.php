<?php

class String {

    public static function objToArray($obj) {
        $arr = array();
        if (is_object($obj)) {
            $vars = get_object_vars($obj);
        } else {
            $vars = $obj;
        }
        if (!empty($vars)) {
            foreach ($vars as $key => $val) {
                if (is_object($val) || is_array($val)) {
                    $arr[$key] = String::objToArray($val);
                } else {
                    $arr[$key] = $val;
                }
            }
        }
        return $arr;
    }

    public static function jsonDecode($json) {
        if (empty($json))
            return array();
        if (!is_string($json))
            return $json;
        $jsonObj = json_decode(stripslashes($json));
        return String::objToArray($jsonObj);
    }

    public static function jsonEncode($data) {
        return json_encode($data);
    }

    public static function cutStr($str, $len, $suffix = ' ... ') {
        $isConverted = false;
        if (String::isUtf8($str)) {
            $str = @iconv('UTF-8', 'GBK', $str);
            $isConverted = true;
        }
        $strLen = strlen($str);
        if ($strLen <= $len) {
            if ($isConverted) {
                $str = @iconv('GBK', 'UTF-8', $str);
            }
            return $str;
        }

        $chars = array();
        for ($i = 0; $i < $len;) {
            $ch = $str{$i};
            if (ord($ch) > 127) {
                $chars[] = $str{$i} . $str{$i + 1};
                $i += 2;
            } else {
                $chars[] = $ch;
                $i += 1;
            }
        }
        $str = join($chars) . $suffix;
        if ($isConverted) {
            $str = @iconv('GBK', 'UTF-8', $str);
        }
        return $str;
    }

    public static function isUtf8($str) {
        return preg_match('/^.*$/u', $str) > 0;
    }

    public static function unescape($str) {
        $str = rawurldecode($str);

        if (false !== preg_match_all("/%u.{4}|&#x.{4};|&#d+;|.+/U", $str, $r)) {
            $ar = $r[0];
            foreach ($ar as $k => $v) {
                if (substr($v, 0, 2) == "%u") {
                    $restr = substr($v, -4);
                    if (false === strpos(PHP_OS, "WIN")) {
                        $restr = substr($restr, 2, 2) . substr($restr, 0, 2);
                    }
                    $ar[$k] = iconv("UCS-2", 'UTF-8', pack("H4", $restr));
                } elseif (substr($v, 0, 3) == "&#x") {
                    $ar[$k] = iconv("UCS-2", 'UTF-8', pack("H4", substr($v, 3, -1)));
                } elseif (substr($v, 0, 2) == "&#") {
                    $ar[$k] = iconv("UCS-2", 'UTF-8', pack("n", substr($v, 2, -1)));
                }
            }
        }
        return join('', $ar);
    }

}

?>

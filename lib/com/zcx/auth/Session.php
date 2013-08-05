<?php
class Session {

    public static function set($key, $val) {

        if ($val == null) {
            $_SESSION[$key] = $val;
            unset($_SESSION[$key]);
        } else {
            $_SESSION[$key] = $val;
        }
    }

    public static function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function clear() {
        $_SESSION = array();
    }

}

?>

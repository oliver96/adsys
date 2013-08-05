<?php

class ServerUtils {
    public static function normalize($path) {
        if (is_null($path)) {
            return null;
        }

        $normalized = $path;

        // replace backslashes '\' with a forward slash '/'
        if (strpos($normalized, '\\') !== false) {
            $normalized = str_replace('\\', '/', $normalized);
        }

        // make sure it begins with a '/'
        if (strpos($normalized, '/') !== 0) {
            $normalized = '/' . $normalized;
        }

        // if ends in directory command ('.' or '..'), append a '/'
        if (substr($normalized, -2) == '/.' || substr($normalized, -3) == '/..') {
            $normalized .= '/';
        }

        // replace references to current directory '/./' or repeated slashes '//'
        $normalized = preg_replace(';/\.?(?=/);', '', $normalized);

        // replace references to parent directory
        while (true) {
            $index = strpos($normalized, '/../');
            if ($index === false) {
                break;
            }
            // trying to go outside of context
            elseif ($index === 0) {
                return null;
            }
            else {
                $index2         = strrpos(substr($normalized, 0, $index - 1), '/');
                $normalized     = substr($normalized, 0, $index2);
                $normalized     .= substr($normalized, $index + 3);
            }
        }

        return $normalized;
    }
}
?>

<?php
if(isset($argv[1])) {
    $queryString = $argv[1];
    $_SERVER['QUERY_STRING'] = $queryString;
}
if(isset($argv[2])) {
    $post = $argv[2];
    $postAry = explode('&', $post);
    if(!empty($postAry)) {
        foreach($postAry as $postLine) {
            list($key, $val) = explode('=', $postLine);
            
            if(!empty($key)) {
                $_POST[$key] = $val;
            }
        }
    }
}
include("index.php");
?>
<?php
require("functions.php");

// 载入基本类
import("com.zcx.core.Action");
import("com.zcx.core.Model");
import("com.zcx.core.Form");
import("com.zcx.core.Request");
import("com.zcx.core.Response");
import('com.zcx.lang.Message');
import("com.zcx.lang.ServerUtils");

/**
 * 核心基类，用于保存一些常用变量，方法
 */
class Core {
    public static $constants = array();
    
    /**
     * 生成url
     *
     * @param array $params URL参数
     */
    
    
    public static function url($params = array()) {
        $config = c('CONFIG');
        return $config->getContext()->url($params);
    }
}
?>

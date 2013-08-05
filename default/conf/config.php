<?php
return array(
    /**
     * 基本设置
     *==========================================================================
     */
     
    // 应用名称
    'appName'       => 'Web Test Frame',
    // 应用目录
    'appDir'        => '/default',
    
    // URL 模式：URL_STANDARD，URL_REWRITE URL_PATHINFO, URL_REST
    'urlMode'       => URL_REST,
    
    /**
     * 数据源设置
     *==========================================================================
     */
     
    // 数据驱动名称
    'driverName'    => 'mysql',
    // 数据驱动链接
    'driverUrl'     => 'mysql://localhost/adsys?user=root&pass=point9*',
    // 数据库连接用户
    'dbUser'        => '',
    // 数据库连接认证密码
    'dbPass'        => '',
    
    /*
    'moduleName'    => 'adplan',
    'actionName'    => 'save',
    */
    
    /**
     * 输出设置
     *==========================================================================
     */
     
     // 输出编码
    'charset'       => 'utf-8',
    
    
    /**
     * 用户认证设置
     *==========================================================================
     */
     
     // 是否开启session功能
    'enableSession' => true,
    
    //'moduleName'    => 'adplan',
    //'actionName'    => 'editInfo',
    
    
    /**
     * 其它设置，可以通过c函数访问
     */
);
?>
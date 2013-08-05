<?php
/**
 *==============================================================================
 * 基本常规函数
 *==============================================================================
 */
 
/**
 * 定义一个全局变量
 *
 * @param string $name 变量名称
 * @param mixed $value 变量值
 */
function def($name, $value) {
    Core::$constants[$name] = $value;
}

/**
 * 获取一个全局变量值
 *
 * @param string $name 变量名称
 *
 * @return mixed 返回变量值
 */
function c($name) {
    if(isset(Core::$constants[$name])) {
        return Core::$constants[$name];
    }
    return null;
}

/**
 * 获取变量引用地址
 *
 * @param mixed $value 变量
 *
 * @return ref 返回引用（指针）
 */
function & ref($value) {
    return $value;
}

/**
 * 载入类库文件
 * @param string $name 类库域名
 */
function import($name) {
    include_once str_replace('.', DS, $name) . '.php';
}

/**
 *==============================================================================
 * 数据库基本函数
 *==============================================================================
 */
 
/**
 * 连接数据库，并返回连接对象
 */
function & connect() {
    $config     = c('CONFIG');
    $context    = $config->getContext();
    $driverName = $context->getDriverName();
    $driverUrl  = $context->getDriverUrl();
    
    if(!empty($driverName)) {
        $dbLinks    = c('DB_LINKS');
        
        if(!isset($dbLinks[$driverName])) {
            // 注册数据库驱动
            DriverManager::registerDriver($driverName);
            
            // 获取数据库操作连接
            $connection             = DriverManager::getConnection($driverUrl);
            $dbLinks[$driverName]   = ref($connection);
            
            def('DB_LINKS', ref($dbLinks));
        }
        return $dbLinks[$driverName];
    }
    else {
        $ret = null;
    }
    return $ret;
}

/**
 * 开始事务处理
 */
function beginTrans() {
    def('TRANSACTION', 1);
    $con = connect();
    $con->beginTrans();
}

/**
 * 回退事务
 */
function rollback() {
    def('TRANSACTION', 0);
    $con = connect();
    $con->rollback();
}

/**
 * 提交事务
 */
function commit() {
    def('TRANSACTION', 0);
    $con = connect();
    $con->commit();
}

/**
 * 关闭数据库连接
 */
function close() {
    $con = connect();
    $con->close();
    
    $config     = c('CONFIG');
    $context    = $config->getContext();
    $driverName = $context->getDriverName();
    
    if(!empty($driverName)) {
        $dbLinks    = c('DB_LINKS');
        if(!isset($dbLinks[$driverName])) {
            $dbLinks[$driverName] = null;
            unset($dbLinks[$driverName]);
            def('DB_LINKS', ref($dbLinks));
        }
    }
}

/**
 *==============================================================================
 * 兼容旧版本的函数
 *==============================================================================
 */
if(!function_exists('lcfirst')) {
    function lcfirst($string = null) {
        if (!$string) return null;
        $string{0} = strtolower($string{0});
        return $string;
    }
}
?>

<?php
include_once "SQLException.php";

class DriverManager {
    private static $driverList = array();
    
    /**
     * 试图建立到给定数据库 DSN 的数据库操作连接
     *
     * @param string $dsn 数据源名称
     * @param string $user 数据库操作用户
     * @param string $pass 数据库用户的密码
     *
     * @return Connection 返回Connection对象
     */
    public static function getConnection($dsn, $user = '', $pass = '') {
        $connection = null;
        if(!empty(self::$driverList)) {
            $params = array();
            
            if('' != $user) {
                $params['username'] = $user;
            }
            if('' != $pass) {
                $params['password'] = $pass;
            }
            foreach(self::$driverList as $driver) {
                $connection = $driver->connect($dsn, $params);
                
                if(null != $connection) {
                    def('DB_LINK', ref($connection));
                    return $connection;
                }
            }
        }
        
        throw new SQLException(Message::get('SQL.10000'));
    }
    
    /**
     * 注册一个数据库驱动
     *
     * @param string $driverName 数据库驱动名称
     */
    public static function registerDriver($driverName) {
        $driverName     = ucfirst(strtolower($driverName));
        $driverClass    = $driverName . 'Driver';
        $driverFile     = "./lib/com/zcx/db/drivers/" . $driverClass . ".php";
        
        $driverList     = & self::$driverList;
        if (file_exists($driverFile) && !isset($driverList[$driverName])) {
            include_once($driverFile);

            $driverList[$driverName] = new $driverClass();
            return ;
        }
        throw new SQLException(Message::get('SQL.10001'));
    }
}
?>

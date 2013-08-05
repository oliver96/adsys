<?php
abstract class Driver {
    /**
     * 开启一个数据库连接
     *
     * @param string $dsn 数据源链接
     * @param array $params 连接参数
     *
     * @return object 返回驱动对象
     */
    abstract public function & connect($dsn, $params = array());
    
    /**
     * 是否已连接上
     */
    abstract public function isConnected();
    
    /**
     * 查询语句
     *
     * @param string $sql SQL语句
     *
     * @return 
     */
    abstract public function query($sql);
    
    /**
     * 执行语句
     *
     * @param string $sql SQL语句
     *
     * @return integer 返回受影响记录数
     */
    abstract public function execute($sql);
    
    /**
     * 获取插入的记录的ID,必须有一字段为AUTO_INCREMENT
     *
     * @return integer
     */
    abstract public function insertid();
    
    /**
     * 开启事务处理
     */
    abstract public function beginTrans();
    
    /**
     * 回退操作
     */
    abstract public function rollback();
    
    /**
     * 提交事务
     */
    abstract public function commit();
    
    /**
     * 关闭一个数据库连接
     */
    abstract public function close();
    
    /**
     * 解析数据源链接
     *
     * @param string 数据源,格式："mysql://localhost/samedata?username=xxx&password=xxx&database=xxx";
     */
    protected function parseDsn($dsn, $params) {
        $dsnAry = array(
            'driver'    => '', 
            'host'      => 'localhost',
            'user'      => '',
            'pass'      => '',
            'name'      => '',
            'charset'   => 'utf8'
        );
        
        if (preg_match("|^(\w+)://([^\/]+)/([^\?]+)[\?]{0,1}(.*)|is", $dsn, $matches)) {
            $driver         = $matches[1];
            $host           = $matches[2];
            $name           = $matches[3];
            $querys         = $matches[4];
            
            $dsnAry['driver']   = $driver;
            $dsnAry['host']     = $host;
            $dsnAry['name']     = $name;
            if(!empty($querys)) {
                $queryAry  = explode('&', $querys);
                
                $tmpParams = array();
                if(!empty($queryAry)) {
                    foreach($queryAry as $one) {
                        list($key, $val) = explode('=', $one, 2);
                        
                        if(isset($dsnAry[$key])) {
                            $tmpParams[$key] = $val;
                        }
                    }
                    $params = array_merge($tmpParams, $params);
                }
            }
        }
        else {
            throw new SQLException(Message::get('SQL.10003'));
            exit(0);
        }
        
        if(!empty($params)) {
            foreach($params as $key => $val) {
                if(isset($dsnAry[$key]) && !empty($val)) {
                    $dsnAry[$key] = $val;
                }
            }
        }
        
        return $dsnAry;
    }
}
?>

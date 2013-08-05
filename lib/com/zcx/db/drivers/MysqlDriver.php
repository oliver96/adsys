<?php
import('com.zcx.db.DataSet');
import('com.zcx.db.drivers.MysqlDataSet');

class MysqlDriver extends Driver {
    private $linkOpts   = array();
    private $link       = null;
   
    public function & connect($dsn, $params = array()) {
        $options        = $this->parseDsn($dsn, $params);
        
        $this->link     = mysql_pconnect(
                            $options['host'], 
                            $options['user'], 
                            $options['pass']
                          ) or die("Error: connect error");
        if(!is_resource($this->link)) {
            throw new SQLException(Message::get('SQL.10004'));
        }
        
        mysql_select_db($options['name'], $this->link) or die(mysql_error());
       
        
        if(isset($options['charset'])) {
            mysql_query(sprintf("set character_set_client=%s", $options['charset']));
            mysql_query(sprintf("set character_set_connection=%s", $options['charset']));
            mysql_query(sprintf("set character_set_results=%s", $options['charset']));
        }
        
        $this->linkOpts     = ref($options);
        $self               = ref($this);
        
        return $self;
    }
    
    /**
     * 是否连接数据库
     *
     * @return boolean
     */
    public function isConnected() {
        return ($this->link != null);
    }
    
    /**
     * 查询语句
     *
     * @param string $sql SQL语句
     *
     * @return 
     */
    public function query($sql) {
        if($this->isConnected()) {
            $res = mysql_query($sql, $this->link) or die(mysql_error());
            return new MysqlDataSet($res);
        }
        else {
            throw new SQLException(Message::get('SQL.10005'));
        }
    }
    
    /**
     * 执行语句
     *
     * @param string $sql SQL语句
     *
     * @return integer 返回受影响记录数
     */
    public function execute($sql) {
        if($this->isConnected()) {
            mysql_query($sql, $this->link) or die(mysql_error());
            return mysql_affected_rows($this->link);
        }
        else {
            throw new SQLException(Message::get('SQL.10005'));
        }
    }
    
    /**
     * 获取插入的记录的ID,必须有一字段为AUTO_INCREMENT
     *
     * @return integer
     */
    public function insertid() {
        return mysql_insert_id($this->link);
    }
    
    /**
     * 开启事务处理
     */
    public function beginTrans() {
        if($this->isConnected()) {
            $this->execute("BEGIN");
            $this->execute("SET AUTOCOMMIT=0");
        }
    }
    
    /**
     * 回退操作
     */
    public function rollback() {
        if($this->isConnected()) {
            $this->execute("ROLLBACK");
        }
    }
    
    public function commit() {
        if($this->isConnected()) {
            $this->execute("COMMIT");
        }
    }
    
    /**
     * 关闭操作连接
     */
    public function close() {
        mysql_close($this->link);
    }
}
?>

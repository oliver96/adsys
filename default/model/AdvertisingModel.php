<?php
class AdvertisingModel extends Model {
    // 表名称
    protected $table    = 'advertisings';
    // 主键
    protected $prikey   = 'id';
    // 字段
    protected $fields   = array('id', 'name', 'adv_id', 'tpl_id', 'size_id', 'optimize', 'created');
    /**
     * 获取表名称
     * 
     * @return string 
     */
    public function getTableName() {
        return $this->table;
    }
    
    /**
     * 获主键名称
     * 
     * @return string 
     */
    public function getPrimaryKey() {
        return $this->prikey;
    }
}
?>

<?php
class MicroModel extends Model {
    // 表名称
    protected $table    = 'micros';
    // 主键
    protected $prikey   = 'id';
    // 字段
    protected $fields   = array('id', 'code', 'name', 'value_type', 'values', 'validate', 'memo');
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

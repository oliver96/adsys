<?php
class SizeModel extends Model {
    // 表名称
    protected $table    = 'sizes';
    // 主键
    protected $prikey   = 'id';
    // 字段
    protected $fields   = array('id', 'name', 'type', 'width', 'height', 'memo', 'created');
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

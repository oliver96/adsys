<?php
class IndustryModel extends Model {
    // 表名称
    protected $table    = 'industries';
    // 主键
    protected $prikey   = 'id';
    // 字段
    protected $fields   = array('id', 'name');
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
    
    /**
     * 获取映射表
     */
    public function getMap() {
        $map    = array();
        
        $list   = $this->getList();
        while($row = $list->nextRow()) {
            $id     = $row->get('id');
            $name   = $row->get('name');
            
            $map[$id] = $name;
        }
        
        return $map;
    }
}
?>

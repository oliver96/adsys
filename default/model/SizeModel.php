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
    
    /**
     * 获取尺寸ID与名称的对映表
     *
     * @return array
     */
    public function getId2NameMap() {
        $map = array();
        $list = $this->getList(array(
            'field' => array('id', 'name', 'width', 'height')
        ));
        while($row = $list->nextRow()) {
            $id = $row->get('id');
            $map[$id] = sprintf("%s(%d x %d)", $row->get('name'), $row->get('width'), $row->get('height'));
        }
        return $map;
    }
}
?>

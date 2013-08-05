<?php
import("com.zcx.db.DataRow");
class MysqlDataRow implements DataRow {
    private $model      = null;
    private $rowData    = array();
    private $updData    = array();
    private $modified   = false;
    
    public function loadRow(& $row) {
        $this->rowData = $row;
    }
    
    /**
     * 设置数据模型
     *
     * @param Model $model 数据模型
     */
    public function setModel(& $model) {
        $this->model = $model;
    }
    
    public function isEmpty() {
        return empty($this->rowData);
    }
    
    /**
     * 给当前记录根据指定的字段设置一个值，如果与旧相同不作处理，更改主键值被忽略
     *
     * @param string $key 字段名称
     * @param mixed $val 字段值
     */
    public function set($key, $val) {
        $primaryKey = $this->model->getPrimaryKey();
        if($primaryKey != $key && isset($this->rowData[$key])) {
            if($val != $this->rowData[$key]) {
                $this->updData[$key] = $val;
                
                if(true !== $this->modified) {
                    $this->modified = true;
                }
            }
        }
    }
    
    /**
     * 获取当前记录根据指定的字段的值
     *
     * @param string $key 字段名称
     *
     * @return mixed
     */
    public function get($key) {
        return isset($this->rowData[$key]) ? $this->rowData[$key] : null;
    }
    
    /**
     * 保存当前记录修改过的内容
     * 
     * @param boolean 返回操作是否成功
     */
    public function save() {
        $status = 0;
        
        if($this->modified == true && !empty($this->updData)) {
            if($this->model->validate($this->updData)) {
                $primaryKey = $this->model->getPrimaryKey();
                $primaryVal = $this->rowData[$primaryKey];
                $status = $this->model->update($this->updData,
                    array(
                        $primaryKey => $primaryVal
                    )
                );
            }
            else {
                $status = -1;
            }
        }
        
        return $status;
    }
    
    /**
     * 返回当前记录的数组格式
     */
    public function toArray() {
        return $this->rowData;
    }
}

?>

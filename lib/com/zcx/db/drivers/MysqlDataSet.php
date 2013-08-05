<?php
import("com.zcx.db.drivers.MysqlDataRow");
class MysqlDataSet implements DataSet {
    private $model      = null;
    private $res        = null;
    private $cursor     = 0;
    private $dataRow    = null;
    
    public function __construct(& $res) {
        $this->res = $res;
        $this->dataRow = new MysqlDataRow();
    }
    
    /**
     * 设置数据模型
     *
     * @param Model $model 数据模型
     */
    public function setModel(& $model) {
        $this->model = $model;
        $this->dataRow->setModel($model);
    }
    
    /**
     * 计算结果集记录数
     *
     * @return integer
     */
    public function count() {
        return mysql_num_rows($this->res);
    }
    
    /**
     * 移到数据首行
     */
    public function first() {
        $this->cursor = 0;
        mysql_data_seek($this->res, $this->cursor);
    }
    
    /**
     * 移到指定的数据行
     */
    public function move($rowindex) {
        $maxCount = $this->count();
        if($maxCount <= $rowindex) {
            $rowindex = $maxCount - 1;
        }
        $this->cursor = $rowindex;
        mysql_data_seek($this->res, $this->cursor);
    }
    
    /**
     * 移到数据尾行
     */
    public function last() {
        $maxCount = $this->count();
        $this->cursor = $maxCount - 1;
        mysql_data_seek($this->res, $this->cursor);
    }
    
    /**
     * 上移一条记录行
     */
    public function prev() {
        $this->cursor --;
        if($this->cursor < 0) {
            $this->cursor = 0;
        }
        mysql_data_seek($this->res, $this->cursor);
    }
    
    /**
     * 下移一条记录行
     */
    public function next() {
        $maxCount = $this->count();
        $this->cursor --;
        if($this->cursor >= $maxCount) {
            $this->cursor = $maxCount - 1;
        }
        mysql_data_seek($this->res, $this->cursor);
    }
    
    /**
     * 获取数据首行记录内容
     *
     * @return array
     */
    public function & firstRow() {
        if($this->cursor != 0) {
            $this->first();
        }
        $this->modified = false;
        $row = mysql_fetch_assoc($this->res);
        $this->dataRow->loadRow($row);
        $row = !$this->dataRow->isEmpty() ? $this->dataRow : null;
        return $row;
    }
    
    /**
     * 获取当前指针下一条数据记录内容
     *
     * @return array
     */
    public function & nextRow() {
        $this->cursor ++;
        $this->modified = false;
        $row = mysql_fetch_assoc($this->res);
        $this->dataRow->loadRow($row);
        $row = !$this->dataRow->isEmpty() ? $this->dataRow : null;
        return $row;
    }
    
    /**
     * 获取数据尾行记录内容
     *
     * @return array
     */
    public function & lastRow() {
        $maxCount = $this->count();
        if($this->cursor != $maxCount - 1) {
            $this->last();
        }
        $this->modified = false;
        $row = mysql_fetch_assoc($this->res);
        $this->dataRow->loadRow($row);
        $row = !$this->dataRow->isEmpty() ? $this->dataRow : null;
        return $row;
    }
}
?>

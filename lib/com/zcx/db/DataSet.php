<?php
interface DataSet {
    public function setModel(& $model);
    public function count();
    public function first();
    public function move($rowindex);
    public function last();
    public function prev();
    public function next();
    public function & firstRow();
    public function & nextRow();
    public function & lastRow();
}
?>

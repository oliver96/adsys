<?php
import("action.CommonAction");
import("model.SizeModel");
class SizeAction extends CommonAction {
    private $sizeModel = null;
    
    public function index() {
        $this->output();
    }
    
    public function edit() {
        $this->output();
    }
    
    public function rows() {
        $totalPage  = 0;
        $page       = $this->request->getParameter('page');
        $pageSize   = $this->request->getParameter('pageSize');
        
        if(empty($page) || $page < 0) $page = 1;
        if(empty($pageSize) || $pageSize < 0) $pageSize = 10;
        
        // 实例化广告尺寸模型对象
        $size       = $this->getSizeModel();
        // 广告尺寸总记录数
        $totalCount = $size->getCount();
        
        $rows = array();
        if($totalCount > 0) {
            // 求出记录总页数
            $totalPage  = ceil($totalCount / $pageSize);
            // 获取广告尺寸列表
            $sizeList    = $size->getList(array(
                'order' => '`id` DESC'
            ), $page, $pageSize);
            
            while($row = $sizeList->nextRow()) {
                $rowAry     = $row->toArray();
                $rows[] = $rowAry;
            }
        }
        
        $this->outputJson(array(
            'params'        => $this->request->getParameters()
            , 'page'        => $page
            , 'pageSize'    => $pageSize
            , 'totalPage'   => $totalPage
            , 'data'        => $rows
        ));
    }
    
    public function getData() {
        $output     = array();
        $id         = $this->request->getParameter('id');
        if($id > 0) {
            $size       = $this->getSizeModel();
            $sizeRow    = $size->getOne(array('id' => $id));
            if($sizeRow) {
                $output = $sizeRow->toArray();
            }
        }
        $this->outputJson($output);
    }
    
    protected function saveData() {
        $status = 0;
        $id     = $this->request->getParameter('id');
        $width  = $this->request->getParameter('width');
        $height = $this->request->getParameter('height');
        $input  = $this->request->getInput();
        
        $size   = new SizeModel();
        
        $existRecords = false;
        if($id > 0) {
            $existRecords = $size->getCount(array('id' => 'not {$id}', 'width' => $width, 'height' => $height));
        }
        else {
            $existRecords = $size->getCount(array('width' => $width, 'height' => $height));
        }
        if($existRecords > 0) {
            $this->outputJson(array(
                'status' => false, 
                'errors' => array(
                    'size_name' => '存在相同尺寸记录'
                )
            ));
        }
        else {
            $input['type'] = 'custom';
            if($id > 0) {
                $size->update($input, array('id' => $id));
            }
            else {
                $id = $size->insert($input);
            }
            $this->outputJson(array('id' => $id));
        }
    }
    
    // 获取尺寸
    public function sizes() {
        $sizes      = $this->getSizeModel();
        $sizeList   = $sizes->getList();
        $rows       = array('system' => array(), 'custom' => array());
        while($sizeRow = $sizeList->nextRow()) {
            $row = $sizeRow->toArray();
            $type = $row['type'];
            $rows[$type][] = $row;
        }
        
        $this->outputJson($rows);
    }
    
    // 获取尺寸模型对象
    private function getSizeModel() {
        if($this->sizeModel == null) {
            $this->sizeModel = new SizeModel();
        }
        return $this->sizeModel;
    }
}

?>

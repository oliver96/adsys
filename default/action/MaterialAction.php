<?php
import("action.CommonAction");
import("model.MaterialModel");

class MaterialAction extends CommonAction {
    public function rows() {
        $totalPage  = 0;
        $page       = $this->request->getParameter('page');
        $pageSize   = $this->request->getParameter('pageSize');
        
        if(empty($page) || $page < 0) $page = 1;
        if(empty($pageSize) || $pageSize < 0) $pageSize = 10;
        
        // 实例化广告主模型对象
        $material = new MaterialModel();
        // 广告主总记录数
        $totalCount = $material->getCount();
        
        $rows = array();
        if($totalCount > 0) {
            // 求出记录总页数
            $totalPage  = ceil($totalCount / $pageSize);
            // 获取广告主列表
            $matList    = $material->getList(array(
                'order' => '`id` DESC'
            ), $page, $pageSize);
            
            while($row = $matList->nextRow()) {
                $rowAry     = $row->toArray();
                $rows[] = $rowAry;
            }
        }
        
        $this->outputJson(array(
            'page'        => $page
            , 'pageSize'    => $pageSize
            , 'totalPage'   => $totalPage
            , 'data'        => $rows
        ));
    }
    
    public function getData() {
        $output     = array();
        $id         = $this->request->getParameter('id');
        if($id > 0) {
            $material   = new MaterialModel();
            $tplRow     = $material->getOne(array('id' => $id));
            if($tplRow) {
                $output = $tplRow->toArray();
            }
        }
        $this->outputJson($output);
    }
    
    protected function saveData() {
        $id     = $this->request->getParameter('id');
        $name   = $this->request->getParameter('name');
        $input  = $this->request->getInput();
        
        $material = new MaterialModel();
        
        $existRecords = false;
        if($id > 0) {
            $existRecords = $material->getCount(array('id' => "not {$id}", 'name' => $name));
        }
        else {
            $existRecords = $material->getCount(array('name' => $name));
        }
        if($existRecords > 0) {
            $this->outputJson(array(
                'status' => false, 
                'errors' => array(
                    'mat_name' => '存在相同名称的记录'
                )
            ));
        }
        else {
            if($id > 0) {
                $material->update($input, array('id' => $id));
            }
            else {
                $id = $material->insert($input);
            }
            $this->outputJson(array('id' => $id));
        }
    }
    
    public function upload() {
        $fileInfo = $_FILES;
        $this->outputJson($fileInfo);
    }
}

?>
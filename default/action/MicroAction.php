<?php
import("action.CommonAction");
import("model.MicroModel");
class MicroAction extends CommonAction {
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
        
        // 实例化广告主模型对象
        $micro      = new MicroModel();
        // 广告主总记录数
        $totalCount = $micro->getCount();
        
        $rows = array();
        if($totalCount > 0) {
            // 求出记录总页数
            $totalPage  = ceil($totalCount / $pageSize);
            // 获取广告主列表
            $microList    = $micro->getList(array(
                'order' => '`id` DESC'
            ), $page, $pageSize);
            
            while($row = $microList->nextRow()) {
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
            $micro      = new MicroModel();
            $microRow   = $micro->getOne(array('id' => $id));
            if($microRow) {
                $output = $microRow->toArray();
            }
        }
        $this->outputJson($output);
    }
    
    protected function saveData() {
        $status = 0;
        $id     = $this->request->getParameter('id');
        $code   = $this->request->getParameter('code');
        $input  = $this->request->getInput();
        
        $micro  = new MicroModel();
        
        $existRecords = false;
        if($id > 0) {
            $existRecords = $micro->getCount(array('id' => 'not {$id}', 'code' => $code));
        }
        else {
            $existRecords = $micro->getCount(array('code' => $code));
        }
        if($existRecords > 0) {
            $this->outputJson(array(
                'status' => false, 
                'errors' => array(
                    'micro_name' => '存在相同模板宏变量记录'
                )
            ));
        }
        else {
            if($id > 0) {
                $micro->update($input, array('id' => $id));
            }
            else {
                $id = $micro->insert($input);
            }
            $this->outputJson(array('id' => $id));
        }
    }
}
?>

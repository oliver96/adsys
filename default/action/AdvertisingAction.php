<?php
import("action.CommonAction");
import("model.AdvertisingModel");
import("model.AdvertiserModel");
import("model.TemplateModel");
import("model.SizeModel");
import("model.MicroModel");
class AdvertisingAction extends CommonAction {
    private $adModel = null;
    
    public function rows() {
        $totalPage  = 0;
        $page       = $this->request->getParameter('page');
        $pageSize   = $this->request->getParameter('pageSize');
        
        if(empty($page) || $page < 0) $page = 1;
        if(empty($pageSize) || $pageSize < 0) $pageSize = 10;
        
        // 实例化广告模型对象
        $advertising    = $this->getAdModel();
        // 广告总记录数
        $totalCount     = $advertising->getCount();
        
        $rows = array();
        if($totalCount > 0) {
            // 求出记录总页数
            $totalPage  = ceil($totalCount / $pageSize);
            
            // 获取广告主映射表
            $advertiser = new AdvertiserModel();
            $advMap     = $advertiser->getId2NameMap();
            
            // 创意形式
            $template   = new TemplateModel();
            $tplMap     = $template->getId2NameMap();
            
            // 广告尺寸
            $size       = new SizeModel();
            $sizeMap    = $size->getId2NameMap();
            
            // 获取广告列表
            $adList     = $advertising->getList(array(
                'order' => '`id` DESC'
            ), $page, $pageSize);
            
            while($row = $adList->nextRow()) {
                $rowAry = $row->toArray();
                
                $advid = $rowAry['adv_id'];
                $rowAry['adv_name'] = $advMap[$advid];
                
                $tplid = $rowAry['tpl_id'];
                $rowAry['tpl_name'] = $tplMap[$tplid];
                
                $sizeid = $rowAry['size_id'];
                $rowAry['size_name'] = $sizeMap[$sizeid];
                
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
            $advertising   = $this->getAdModel();
            $adRow         = $advertising->getOne(array('id' => $id));
            if($adRow) {
                $output = $adRow->toArray();
            }
        }
        $this->outputJson($output);
    }
    
    protected function saveData() {
        $status     = 0;
        $id         = $this->request->getParameter('id');
        $name       = $this->request->getParameter('name');
        $input      = $this->request->getInput();
        
        $advertising    = $this->getAdModel();
        
        $existRecords   = false;
        if($id > 0) {
            $existRecords = $advertising->getCount(array('id' => "not '{$id}'", 'name' => $name));
        }
        else {
            $existRecords = $advertising->getCount(array('name' => $name));
        }
        if($existRecords > 0) {
            $this->outputJson(array(
                'status' => false, 
                'errors' => array(
                    'adtpl_name' => '存在相同广告记录'
                )
            ));
        }
        else {
            if($id > 0) {
                $advertising->update($input, array('id' => $id));
            }
            else {
                $id = $advertising->insert($input);
            }
            $this->outputJson(array('id' => $id));
        }
    }
    
    // 获取广告模型
    private function getAdModel() {
        if($this->adModel == null) {
            $this->adModel = new AdvertisingModel();
        }
        return $this->adModel;
    }
    
    // 根据模板ID获取创意板板的宏变量
    public function micros() {
        $rows = array();
        
        $tplid = $this->request->getParameter('id');
        $template = new TemplateModel();
        $tplRow = $template->getOne(array('id' => $tplid));
        $code = $tplRow->get('code');
        if(preg_match_all('|\[([\w]+)\]|Uis', $code, $matches)) {
            if(!empty($matches) && !empty($matches[1])) {
                $microNames = $matches[1];
                
                $micro = new MicroModel();
                $microList = $micro->getList(array('code' => "IN ('" . implode("','", $microNames) . "')"));
                while($microRow = $microList->nextRow()) {
                    $rows[] = $microRow->toArray();
                }
            }
        }
        
        $this->outputJson($rows);
    }
}
?>

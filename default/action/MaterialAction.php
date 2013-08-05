<?php
import("action.CommonAction");
import("model.MaterialModel");
import("model.AdvertiserModel");
class MaterialAction extends CommonAction {
    public function index() {
        $this->output();
    }
    
    public function edit() {
        $this->output();
    }
    
    protected function getData() {
        
    }
    
    protected function saveData() {
        
    }
    
    // 获取广告主
    public function advertisers() {
        $advertiser = new AdvertiserModel();
        $advList    = $advertiser->getList();
        $rows       = array();
        while($advRow = $advList->nextRow()) {
            $rows[] = $advRow->toArray();
        }
        
        $this->outputJson($rows);
    }
}

?>

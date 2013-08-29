<?php
import("action.CommonAction");
import("model.TemplateModel");
class TemplateAction extends CommonAction {
    private $templateModel = null;
    
    public function rows() {
        $totalPage  = 0;
        $page       = $this->request->getParameter('page');
        $pageSize   = $this->request->getParameter('pageSize');
        
        if(empty($page) || $page < 0) $page = 1;
        if(empty($pageSize) || $pageSize < 0) $pageSize = 10;
        
        // 实例化广告模板模型对象
        $template   = $this->getTemplateModel();
        // 广告模板总记录数
        $totalCount = $template->getCount();
        
        $rows = array();
        if($totalCount > 0) {
            // 求出记录总页数
            $totalPage  = ceil($totalCount / $pageSize);
            // 获取广告模板列表
            $tplList    = $template->getList(array(
                'order' => '`id` DESC'
            ), $page, $pageSize);
            
            // 允许的素材类型
            $matTypeMap = array('text' => '文本', 'image' => '图片', 'video' => '视频');
            while($row = $tplList->nextRow()) {
                $rowAry     = $row->toArray();
                $matTypes   = explode(',', $rowAry['mat_types']);

                $rowAry['mat_type_text'] = '';
                if(!empty($matTypes)) {
                    $texts = array();

                    foreach($matTypes as $matType) {
                        $texts[] = isset($matTypeMap[$matType]) ? $matTypeMap[$matType] : $matType;
                    }
                    $rowAry['mat_type_text'] = implode(',', $texts);
                    unset($rowAry['mat_types']);
                }
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
            $template   = $this->getTemplateModel();
            $tplRow     = $template->getOne(array('id' => $id));
            if($tplRow) {
                $output = $tplRow->toArray();
            }
        }
        $this->outputJson($output);
    }
    
    protected function saveData() {
        $status     = 0;
        $id         = $this->request->getParameter('id');
        $name       = $this->request->getParameter('name');
        $input      = $this->request->getInput();
        
        $template   = $this->getTemplateModel();
        
        $existRecords = false;
        if($id > 0) {
            $existRecords = $template->getCount(array('id' => "not '{$id}'", 'name' => $name));
        }
        else {
            $existRecords = $template->getCount(array('name' => $name));
        }
        if($existRecords > 0) {
            $this->outputJson(array(
                'status' => false, 
                'errors' => array(
                    'adtpl_name' => '存在相同模板记录'
                )
            ));
        }
        else {
            if(isset($input['mat_types']) && is_array($input['mat_types'])) {
                $input['mat_types'] = implode(',', $input['mat_types']);
            }
            if($id > 0) {
                $template->update($input, array('id' => $id));
            }
            else {
                $id = $template->insert($input);
            }
            $this->outputJson(array('id' => $id));
        }
    }
    
    // 获取创意模板
    public function templates() {
        $template   = $this->getTemplateModel();
        $tplList    = $template->getList();
        $rows       = array('system' => array(), 'custom' => array());
        while($tplRow = $tplList->nextRow()) {
            $row = $tplRow->toArray();
            $type = $row['type'];
            $rows[$type][] = $row;
        }
        
        $this->outputJson($rows);
    }
    
    // 获取广告创意模板模型对象
    private function getTemplateModel() {
        if($this->templateModel == null) {
            $this->templateModel = new TemplateModel();
        }
        return $this->templateModel;
    }
}
?>

<?php

import("action.CommonAction");

import("model.AdvertiserModel");

class ResourceAction extends CommonAction {

    public function advertisers() {
        $this->output();
    }

    public function editAdvertiser() {
        $this->output();
    }

    public function materials() {
        $this->output();
    }

    public function editMaterial() {
        $this->output();
    }

    public function banners() {
        $this->output();
    }
    
    public function editBanner() {
        $this->output();
    }

    public function advertisings() {
        $this->output();
    }

    public function slotList() {
        $this->output();
    }

    public function sizeList() {
        $this->output();
    }

    /**
     * 获取广告主列表数据 
     */
    public function ajaxAdvertisers() {
        $output = array(
            'totalPage' => 0,
            'page' => 0,
            'pageSize' => 10,
            'data' => array()
        );

        $page = $this->request->getParameter('page');
        $psize = $this->request->getParameter('pageSize');

        if ($page <= 0) {
            $page = 1;
        }
        if ($psize <= 0) {
            $psize = 10;
        }

        $advModel = new AdvertiserModel();

        $options = array();
        $advCount = $advModel->getCount($options);

        if ($advCount > 0) {
            $advList = $advModel->getList($options, $page, $psize);

            $rows = array();
            while ($advRow = $advList->nextRow()) {
                $rows[] = $advRow->toArray();
            }

            $output['totalPage'] = ceil($advCount / $psize);
            $output['data'] = $rows;
        }

        $output['page'] = $page;
        $output['pageSize'] = $psize;

        $this->outputJson($output);
    }

}

?>

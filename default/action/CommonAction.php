<?php

class CommonAction extends Action {

    public function initAction(& $context, & $request, & $response) {
        parent::initAction($context, $request, $response);

        $navs = array(
            array(
                'header' => '广告',
                'childs' => array(
                    array(
                        'text' => '广告主',
                        'icon' => 'icon-home',
                        'url' => Core::url(array('m' => 'advertiser', 'a' => 'index'))
                    ),
                    array(
                        'text' => '素材',
                        'icon' => 'icon-th-list',
                        'url' => Core::url(array('m' => 'material', 'a' => 'index'))
                    ),
                    array(
                        'text' => '创意',
                        'icon' => 'icon-picture',
                        'url' => Core::url(array('m' => 'resource', 'a' => 'banners'))
                    ),
                    array(
                        'text' => '广告',
                        'icon' => 'icon-bullhorn',
                        'url' => Core::url(array('m' => 'resource', 'a' => 'advertisings'))
                    )
                )
            ),
            array(
                'header' => '媒体',
                'childs' => array(
                    array(
                        'text' => '广告位',
                        'icon' => ' icon-list-alt',
                        'url' => Core::url(array('m' => 'publisher', 'a' => 'slots'))
                    )
                )
            ),
            array(
                'header' => '订单',
                'childs' => array(
                    array(
                        'text' => '订单',
                        'icon' => 'icon-shopping-cart',
                        'url' => Core::url(array('m' => 'order', 'a' => 'orders'))
                    ),
                    array(
                        'text' => '订单项',
                        'icon' => 'icon-th-list',
                        'url' => Core::url(array('m' => 'order', 'a' => 'orderItems'))
                    )
                )
            ),
            array(
                'header' => '公共',
                'childs' => array(
                    array(
                        'text' => '尺寸',
                        'icon' => 'icon-resize-full',
                        'url' => Core::url(array('m' => 'size', 'a' => 'index'))
                    ),
                    array(
                        'text' => '模板宏定义',
                        'icon' => 'icon-tasks',
                        'url' => Core::url(array('m' => 'micro', 'a' => 'index'))
                    ),
                    array(
                        'text' => '创意模板',
                        'icon' => 'icon-tasks',
                        'url' => Core::url(array('m' => 'template', 'a' => 'index'))
                    )
                )
            ),
            array(
                'header' => '报表',
                'childs' => array(
                    array(
                        'text' => '广告',
                        'icon' => 'icon-retweet',
                        'url' => Core::url(array('m' => 'order', 'a' => 'orderList'))
                    ),
                    array(
                        'text' => '广告位',
                        'icon' => 'icon-th-large',
                        'url' => Core::url(array('m' => 'order', 'a' => 'orderItemList'))
                    )
                )
            ),
            array(
                'header' => '系统',
                'childs' => array(
                    array(
                        'text' => '设置',
                        'icon' => 'icon-cog',
                        'url' => Core::url(array('m' => 'order', 'a' => 'orderList'))
                    ),
                    array(
                        'text' => '用户',
                        'icon' => 'icon-user',
                        'url' => Core::url(array('m' => 'order', 'a' => 'orderItemList'))
                    )
                )
            )
        );

        $this->assign('navs', $navs);
        
        $params = $this->request->getParameters();
        if(empty($params)) $params = array();
        $this->assign('params', String::jsonEncode($params));
        
        file_put_contents("/tmp/adsys.log", $_SERVER['PHP_SELF'] . "\r\n", FILE_APPEND);
    }
    
    // 公共“添加”，“删除”，“编辑”的接口代理
    public function api() {
        $method = $this->request->getMethod();
        switch(strtoupper($method)) {
            case 'GET' :
                $this->getData();
            break;
            case 'PUT' :
                $this->saveData();
            break;
        }
    }
}

?>

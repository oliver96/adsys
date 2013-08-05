<?php
import('com.zcx.core.StandardContext');

class ContextConfig {
    private $context    = null;
    private $config     = array();
    
    /**
     * 构造函数
     */
    public function __construct($appDir = null) {
        $this->context = ref(new StandardContext()); 
        $this->context->setAppDir($appDir);
        
        $this->start();
    }
    
    /**
     * 开始执行
     */
    private function start() {
        $this->loadConfig();
    }
    
    /**
     * 载入配置文件
     */
    private function loadConfig() {
        $this->context->loadConfig();
    }
    
    /**
     * 获取配置对象，从web.conf文件导入
     */
    public function & getContext() {
        return $this->context;
    }
}
?>

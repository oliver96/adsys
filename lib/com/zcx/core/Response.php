<?php
class Response {
    protected $context        = null;
    protected $request        = null;
    
    protected $charset        = 'utf-8';
    
    protected $varibles       = array();
    
    public function __construct(& $context) {
        $this->context = $context;
    }
    
    public function setRequest(& $request) {
        $this->request = $request;
    }
    
    public function setHeader($key, $value) {
        header(sprintf("%s: %s", $key, $value));
    }
    
    public function setCharset($charset) {
        $this->charset = $charset;
    }
    
    public function getCharset() {
        return $this->charset;
    }
    
    public function setVar($key, $value) {
        $this->variables[$key] = $value;
    }
    
    public function output($tplName = '') {
        $tplFile    = '';
        $viewPath   = $this->context->getViewPath();
        $extName    = $this->context->getViewExt();
        if('' == $tplName) {
            $moduleName = strtolower($this->context->getModuleName());
            $actionName = strtolower($this->context->getActionName());
            $tplName    = $moduleName . DS . $actionName . $extName;
        }
        else {
            if(substr($tplName, -5) == $extName) {
                $tplName = str_replace(array('\\', '/'), DS, $tplName);
            }
            else {
                $tplName = str_replace(array('\\', '/'), DS, $tplName) . $extName;
            }
        }
        $tplFile = $viewPath . DS . $tplName;
        if(file_exists($tplFile)) {
            //ob_clean();
            $charset = $this->getCharset();
            $this->setHeader('Content-Type', 'text/html; charset=' . $charset);
            $parameters = $this->request->getParameters();
            if(!empty($parameters) && is_array($parameters)) {
                extract($parameters, EXTR_PREFIX_ALL, 'req');
            }
            if(!empty($this->variables) && is_array($this->variables)) { 
                extract($this->variables);
            }
            include($tplFile);
        }
        else {
            die($tplName . "文件不存在");
        }
    }
    
    public function outputJson($data = array()) {
        //ob_clean();
        $charset = $this->getCharset();
        $this->setHeader('Content-Type', 'application/json; charset=' . $charset);
        echo json_encode($data);
    }
    
    public function outputJs($content) {
        $this->setHeader('Content-Type', 'application/javascript');
        echo $content;
    }
}
?>

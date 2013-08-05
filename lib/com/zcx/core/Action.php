<?php
abstract class Action {
    
    protected $context      = null;
    protected $request      = null;
    protected $response     = null;
    
    public function initAction(& $context, & $request, & $response) {
        
        $this->context      = $context;
        $this->request      = $request;
        $this->response     = $response;
        
        $this->assign('CONTEXT', ref($this->context));
        $this->assign('GROUP_NAME', $this->context->getGroupName());
        $this->assign('MODULE_NAME', $this->context->getModuleName());
        $this->assign('ACTION_NAME', $this->context->getActionName());
        $this->assign('WEB_ROOT', $this->request->getContextPath());
        $this->assign('APP_PATH', $this->context->getAppDir());
        $this->assign('ACTION_PATH', $this->context->getActionPath());
        $this->assign('MODEL_PATH', $this->context->getModelPath());
        $this->assign('VIEW_PATH', $this->context->getViewPath());
    }
    
    protected function assign($key, $value) {
        $this->response->setVar($key, $value);
    }
    
    protected function redirect($url) {
        $this->response->setHeader('Location', $url);
        exit(1);
    }
    
    protected function output($tplName = '') {
        $this->response->output($tplName);
    }
    
    protected function outputJson($data) {
        $this->response->outputJson($data);
    }
    
    protected function outputJs($data) {
        $this->response->outputJs($data);
    }
}
?>

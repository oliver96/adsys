<?php
def('StandardContext::APP_DIR', '/default');
def('StandardContext::APP_CONFIG', '/conf/config.php');


/**
 * url模式：
 * STANDARD（http://localhost/appName/index.php?[g=groupName&]m=moduleName&a=actionName&id=1）
 * REWRITE（http://localhost/appName/[groupName/]moduleName/actionName/id/1/）
 * PATHINFO（http://localhost/appName/index.php/[groupName/]moduleName/actionName/id/1/）
 * REST（http://localhost/appName/index.php/[groupName/]moduleName/[id]）
 */
define('URL_STANDARD', 1);
define('URL_REWRITE', 2);
define('URL_PATHINFO', 3);
define('URL_REST', 4);

class StandardContext {
    /**
     * 应用名称
     */
    //private $appName        = '';
    
    /**
     * 应用目录
     */
    private $appDir         = null;
    
    /**
     * 数据配置
     */
    private $driverName     = '';
    private $driverUrl      = '';
    private $dbUser         = '';
    private $dbPass         = '';
    
    /**
     * 请求对象
     */
    private $request        = null;
    
    /**
     * 回应对象
     */
    private $response       = null;
    private $charset        = 'utf-8';
    
    
    /**
     * URL模式： 
     * URL_STANDARD(default), 
     * URL_REWRITE, 
     * URL_PATHINFO, 
     * URL_REST 全称为　RESTful Web
     */
    private $urlMode        = URL_STANDARD;
    
    private $actionPath     = 'action';
    private $viewPath       = 'view';
    private $modelPath      = 'model';
    
    /**
     * 是否开启分组，默认为否
     */
    private $group          = false;
    
    private $groupIndex     = 'g';
    private $moduleIndex    = 'm';
    private $actionIndex    = 'a';
    
    private $groupName      = 'index';
    private $moduleName     = 'index';
    private $actionName     = 'index';
    
    
    private $viewExt        = '.html';
    
    /**
     * 解析app目录
     */
    private function resolveAppDir($appDir) {
        if (!is_null($appDir)) {
            $pos = strpos($appDir, '/conf/');
            if ($pos !== false) {
                $appDir = ROOT_PATH . substr($appDir, 0, $pos);
            }
            else {
                $appDir = ROOT_PATH . $appDir;
            }
        }
        else {
            $appDir = ROOT_PATH . c('StandardContext::APP_DIR');
        }                            
        $appDir = str_replace(array('\\', '/'), DS, $appDir);
        
        return $appDir;
    }
    
    /**
     * 设置应用目录
     *
     * @param string $appDir 应用目录
     */
    public function setAppDir($appDir) {
        $appDir = $this->resolveAppDir($appDir);
        if(file_exists($appDir)) {
            $this->appDir = $appDir;
            set_include_path($appDir);
        }
    }
    
    /**
     * 获取应用目录
     */
    public function getAppDir() {
        return $this->appDir;
    }
    
    /**
     * 设置数据源名称（DSN)
     *
     * @param string $dsn 数据源名称
     */
    public function setDsn($dsn) {
        $this->dsn = $dsn;
    }
    
    /**
     * 获取数据源名称
     */
    public function getDsn() {
        return $this->dsn;
    }
    
    /**
     * 载入配置文件
     *
     * @param string $file 配置文件
     */ 
    public function loadConfig($confFile = '') {
        static $loadedConfFiles = array();
        
        if($confFile == '') {
            $configFile     = $this->appDir . c('StandardContext::APP_CONFIG');
        }
        else {
            $configFile     = $this->appDir . $confFile;
        }
        
        if(isset($loadedConfFiles[$configFile])) return;
        
        if(file_exists($configFile)) {
            $config     = require($configFile);
            if(!empty($config)) {
                foreach($config as $name => $value) {
                    if(property_exists($this, $name)) {
                        if('appDir' == $name) {
                            // null;
                        }
                        else {
                            $this->{$name} = $value;
                        }
                    }
                    else {
                        def(strtoupper($name), $value);
                    }
                }
            }
            $loadedConfFiles[$configFile] = true;
        }
    }
    
    /**
     * 设定url模式方式：URL_STANDARD, URL_REWRITE, URL_PATHINFO, URL_REST
     *
     * @param integer $mode URL模式
     */
    public function setUrlMode($mode) {
        $this->urlMode = $mode;
    }
    
    /**
     * 取得URL模式
     *
     * @return integer
     */
    public function getUrlMode() {
        return $this->urlMode;
    }
    
    /**
     * 设置是否分组
     *
     * @param boolean $group 是否分组，true 为 分组，否则反之
     */
    public function setEnableGroup($group) {
        $this->group = $group;
    }
    
    /**
     * 返回是否开启分组功能
     *
     * @return boolean
     */
    public function enableGroup() {
        return $this->group;
    }
    
    /**
     * 获取分组索引名称
     *
     * @return string
     */
    public function getGroupIndex() {
        return $this->group ? $this->groupIndex : null;
    }
    
    /**
     * 获取模块索引名称
     *
     * @return string
     */
    public function getModuleIndex() {
        return $this->moduleIndex;
    }
    
    /**
     * 获取动作索引名称
     */
    public function getActionIndex() {
        return $this->actionIndex;
    }
    
    /**
     * 设定分组名称
     *
     * @param string $name 分组名称
     */
    public function setGroupName($name) {
        if(!empty($name)) {
            $this->groupName = $name;
        }
    }
    
    /**
     * 获取分组名称
     *
     * @return string
     */
    public function getGroupName() {
        return $this->groupName;
    }
    
    
    /**
     * 设定模块名称
     *
     * @param string $name 模块名称
     */
    public function setModuleName($name) {
        if(!empty($name)) {
            $this->moduleName = $name;
        }
    }
    
    
    /**
     * 获取模块名称
     *
     * @return string
     */
    public function getModuleName() {
        return $this->moduleName;
    }
    
    /**
     * 设定动作名称
     *
     * @param string $name
     */
    public function setActionName($name) {
        if(!empty($name)) {
            $this->actionName = $name;
        }
    }
    
    /**
     * 获取动作名称
     *
     * @return string
     */
    public function getActionName() {
        return $this->actionName;
    }
    
    /**
     * 设置视图文件的扩展名
     * 
     * @param type $ext
     */
    public function setViewExt($ext) {
        $this->viewExt = $ext;
    }
    
    /**
     * 获取视图文件的扩展名
     * 
     * @return type
     */
    public function getViewExt() {
        return $this->viewExt;
    }
    /**
     * 获取控制器路径
     *
     * @return string
     */
    public function getActionPath() {
        return $this->getAbsPath($this->actionPath);
    }
    
    /**
     * 获取视图路径
     *
     * @return string
     */
    public function getViewPath() {
        return $this->getAbsPath($this->viewPath);
    }
    
    /**
     * 获取模型路径
     *
     * @return string
     */
    public function getModelPath() {
        return $this->getAbsPath($this->modelPath);
    }
    
    /**
     * 根据指定的相对APP_DIR的路径获取绝对路径
     * 
     * @param string $path 相对路径
     *
     * @return string
     */
    private function getAbsPath($path) {
        $absPath    = $this->appDir;
        
        if(true == $this->group) {
            $absPath .= DS . $this->groupName;
        }
        $path       = str_replace(array('\\', '/'), DS, $path);
        $absPath    .= DS. trim($path, DS);
        
        return $absPath;
    }
    
    /**
     * 获取相对的路径
     * 
     * @param type $path　绝对路径
     * 
     * @return string
     */
    private function getRelativePath($path) {
        return str_replace($this->appDir, '', $path);
    }
    
    public function invoke(& $request, & $response) {
        $this->request  = $request;
        $this->response = $response;
        $gidx           = $this->groupIndex;
        $midx           = $this->moduleIndex;
        $aidx           = $this->actionIndex;
        
        // 载入分组模块的配置文件
        if($this->enableGroup()) {
            $confFile = DS . $this->getGroupName() . c('StandardContext::APP_CONFIG');
            $this->loadConfig($confFile);
        }
        
        $this->setGroupName($request->getParameter($gidx));
        $this->setModuleName($request->getParameter($midx));
        $this->setActionName($request->getParameter($aidx));
        
        $response->setCharset($this->charset);
    
        $moduleName     = ucfirst($this->getModuleName());
        $actionName     = $this->getActionName();
        
        $className      = $moduleName . 'Action';
        $classFile      = $this->getActionPath() . DS . $className . '.php';
        
        if(file_exists($classFile)) {
            require($classFile);
            if(class_exists($className)) {
                $instance   = ref(new $className());
                $method     = lcfirst($actionName);
                
                if(method_exists($instance, $method) && is_a($instance, 'Action')) {
                    $instance->initAction($this, $request, $response);
                    $instance->$method();
                }
                else {
                    die(sprintf("方法%s不存在或者动作类不是有效的!!!", $method));
                }
            }
            else {
                die(sprintf("模块名称%s未载入!!!", $className));
            }
        }
        else {
            die(sprintf("模块文件%s不存在!!!", $this->getRelativePath($classFile)));
        }
    }
    
    /**
     * 设定数据库驱动名称
     *
     * @param string $name 驱动名称
     */
    public function setDriverName($name) {
        $this->driverName = $name;
    }
    
    /**
     * 获取数据库驱动名称
     *
     * @return string
     */
    public function getDriverName() {
        return $this->driverName;
    }
    
    /**
     * 设置数据驱动链接
     *
     * @param string $url 数据驱动链接
     */
    public function setDriverUrl($url) {
        $this->driverUrl = $url;
    }
    
    /**
     * 获取数据驱动链接
     *
     * @return string
     */
    public function getDriverUrl() {
        return $this->driverUrl;
    }
    
    /**
     * 生成URL链接
     *
     * @param array $params URL参数
     */
    public function url($params) {
        $urlMode    = $this->getUrlMode();
        
        $group      = $this->enableGroup();
        $gName      = $this->getGroupName();
        $mName      = $this->getModuleName();
        $aName      = $this->getActionName();
        
        $pathAry    = array();
        $paramAry   = array();
        
        if($group) {
            $gIndex = $this->getGroupIndex();
            if(isset($params[$gIndex])) {
                $gName = $params[$gIndex];
                unset($params[$gIndex]);
            }
        }
        $mIndex = $this->getModuleIndex();
        if(isset($params[$mIndex])) {
            $mName = $params[$mIndex];
            unset($params[$mIndex]);
        }
        $aIndex = $this->getActionIndex();
        if(isset($params[$aIndex])) {
            $aName = $params[$aIndex];
            unset($params[$aIndex]);
        }
        
        if(!empty($params)) {
            foreach($params as $key => $val) {
                if(URL_STANDARD == $urlMode) {
                    $paramAry[] = sprintf("%s=%s", $key, urlencode($val));
                }
                else if(URL_REWRITE == $urlMode || URL_PATHINFO == $urlMode) {
                    $paramAry[] = sprintf("%s/%s", $key, urlencode($val));
                }
            }
        }
        
        $url = '';
        $uri = URL_REWRITE == $urlMode ? '/' : '/index.php';
        switch($urlMode) {
            case URL_REWRITE :
            case URL_PATHINFO :
            case URL_REST :
                if($group) {
                    $pathAry[] = $gName;
                }
                $pathAry[] = $mName;
                $pathAry[] = $aName;
                $urlPath    = implode('/', $pathAry);
                $urlParam   = implode('/', $paramAry);
                $url        = sprintf("%s/%s/%s", $uri, $urlPath, $urlParam);
                $url        = rtrim($url, '/');
            break;
            case URL_STANDARD :
                if($group) {
                    $pathAry[] = sprintf("%s=%s", $gIndex, $gName);
                }
                $pathAry[]  = sprintf("%s=%s", $mIndex, $mName);
                $pathAry[]  = sprintf("%s=%s", $aIndex, $aName);
                $urlPath    = implode('&', $pathAry);
                $urlParam   = implode('&', $paramAry);
                $url        = sprintf("%s?%s&%s", $uri, $urlPath, $urlParam);
                $url        = rtrim($url, '&');
            break;
        }
        $url = $this->request->getContextPath() . $url;
        return $url;
    }
}
?>

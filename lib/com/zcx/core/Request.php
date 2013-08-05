<?php
import('com.zcx.utils.String');

class Request {
    private $context        = null;
    private $request        = null;
    
    private $uri            = '';
    private $method         = 'GET';    // POST，GET，PUT或DELETE
    private $protocol       = 'HTTP';   // HTTP, HTTPS
    private $queryStr       = '';
    private $scheme         = '';
    private $secure         = false;
    private $ctxPath        = '';
    
    private $headers        = array();
    private $parameters     = array();
    
    public function __construct(& $context) {
        $this->context = $context;
    }

    public function setResponse(& $request) {
        $this->request = $request;
    }
    
    public function setRequestURI($uri) {
        $this->uri = $uri;
    }
    
    public function getRequestURI() {
        return $this->uri;
    }
    
    public function setMethod($method) {
        $this->method = $method;
    }
    
    public function getMethod() {
        return $this->method;
    }
    
    public function setProtocol($protocol) {
        $this->protocol = $protocol;
    }
    
    public function getProtocol() {
        return $this->protocol;
    }
    
    public function setQueryString($queryString) {
        $this->queryStr = String::unescape($queryString);
    }
    
    public function getQueryString() {
        return $this->queryStr;
    }
    
    public function setScheme($scheme) {
        $this->scheme = $scheme;
    }
    
    public function getScheme() {
        return $this->scheme;
    }
    
    public function setSecure($secure) {
        $this->secure = $secure;
    }
    
    public function getSecure() {
        return $this->secure;
    }
    
    public function setContextPath($contextPath) {
        $this->ctxPath = $contextPath;
    }
    
    public function getContextPath() {
        return $this->ctxPath;
    }
    
    public function getGroupName() {
    }
    
    public function setHeaders(& $headers) {
        $this->headers = $headers;
    }
    
    public function getHeaders() {
        return $this->headers;
    }
    
    public function getHeader($name) {
        return isset($this->headers[$name]) ? $this->headers[$name] : null;
    }
    
    public function setInput(& $input) {
        $this->input = $input;
    }
    
    public function & getInput() {
        return $this->input;
    }
    
    public function setParameter($name, $value) {
        $this->parameters[$name] = $value;
    }
    
    public function getParameter($name) {
        $this->parseParameters();
        return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
    }
    
    public function getParameters() {
        $this->parseParameters();
        return $this->parameters;
    }
    
    private function parseParameters() {
        static $hasParsed = false;
        
        if ($hasParsed) {
            return;
        }
        
        $urlMode        = $this->context->getUrlMode();
        $enableGroup    = $this->context->enableGroup();

        $this->parameters = array();
        if(URL_STANDARD == $urlMode) {
            $groupIndex     = $this->context->getGroupIndex();
            $moduleIndex    = $this->context->getModuleIndex();
            $actionIndex    = $this->context->getActionIndex();
        
            $qStr           = $this->getQueryString();
            
            parse_str($this->getQueryString(), $results);
            foreach ($results as $name => $value) {
                $name = str_replace(chr(183), '.', $name);
                
                if(true == $enableGroup && $name == $groupIndex) {
                    $this->context->setGroupName($value);
                }
                else if($name == $moduleIndex) {
                    $this->context->setModuleName($value);
                }
                else if($name == $actionIndex) {
                    $this->context->setActionName($value);
                }
                else {
                    $this->parameters[$name] = $value;
                }
            }
        }
        else if(URL_PATHINFO == $urlMode || URL_REWRITE == $urlMode) {
            $qStr = trim(substr($this->uri, strlen($this->ctxPath)), '/');
            $qAry = explode('/', $qStr);
            
            if(true == $enableGroup) {
                if(isset($qAry[0])) {
                    $this->context->setGroupName($qAry[0]);
                    array_shift($qAry);
                }
            }
            if(isset($qAry[0])) {
                $this->context->setModuleName($qAry[0]);
                array_shift($qAry);
            }
            if(isset($qAry[0])) {
                $this->context->setActionName($qAry[0]);
                array_shift($qAry);
            }
            for($i = 0, $l = count($qAry); $i < $l; $i += 2) {
                $name   = $qAry[$i];
                $value  = $qAry[$i + 1];
                
                $name = str_replace(chr(183), '.', $name);
                $this->parameters[$name] = $value;
            }
        }
        else if(URL_REST == $urlMode) {
            $qStr = trim(substr($this->uri, strlen($this->ctxPath)), '/');
            $qAry = explode('/', $qStr);
            
            if(true == $enableGroup) {
                if(isset($qAry[0])) {
                    $this->context->setGroupName($qAry[0]);
                    array_shift($qAry);
                }
            }
            if(isset($qAry[0])) {
                $this->context->setModuleName($qAry[0]);
                array_shift($qAry);
            }
            if(isset($qAry[0])) {
                $this->context->setActionName($qAry[0]);
                array_shift($qAry);
            }
            else {
                switch(strtoupper($this->method)) {
                    case 'GET' :
                        $this->context->setActionName('index');
                        break;
                    case 'POST' :
                        $this->context->setActionName('add');
                        break;
                    case 'PUT' :
                        $this->context->setActionName('update');
                        break;
                    case 'DELETE' :
                        $this->context->setActionName('delete');
                        break;
                }
            }
            if(isset($qAry[0])) {
                $this->parameters['id'] = intval($qAry[0]);
                array_shift($qAry);
            }
            
            //　获取payload数据
            $reqBody    = file_get_contents('php://input');
            if(!empty($reqBody)) {
                $reqArray   = String::jsonDecode($reqBody);
                if(!empty($reqArray)) {
                    $this->setInput($reqArray);
                }
            }
        }
        
        foreach ($this->getInput() as $name => $value) {
            $name = str_replace(chr(183), '.', $name);
            if(is_string($value)) {
                $this->parameters[$name] = String::unescape($value);
            }
            else {
                $this->parameters[$name] = $value;
            }
        }
        
        $hasParsed = true;
    }
}
?>

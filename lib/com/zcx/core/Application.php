<?php
class Application {
    private $context        = null;
    private $request        = null;
    private $response       = null;
    
    function Application(& $context) {
        $this->context      = $context;
        
        $this->request      = ref(new Request($context));
        $this->response     = ref(new Response($context));
    }
    
    public function run() {
        $this->request->setResponse($this->response);
        $this->response->setRequest($this->request);

        $this->parseRequest($_SERVER);
        $this->parseHeaders($_SERVER);

        $this->response->setHeader('Date', gmdate('D, d M Y H:i:s T'));
        $this->context->invoke($this->request, $this->response);
    }
    
    private function parseRequest(& $input) {
        $contextPath    = substr($input['SCRIPT_NAME'], 0, strrpos($input['SCRIPT_NAME'], '/'));
        $requestURI     = $contextPath . (isset($input['PATH_INFO']) ? $input['PATH_INFO'] : '');


        $normalizedURI  = ServerUtils::normalize($requestURI);

        if ($contextPath == '' && $normalizedURI == '') {
            $normalizedURI = '/';
        }
        
        $this->request->setRequestURI($normalizedURI);

        $this->request->setMethod(isset($input['REQUEST_METHOD']) ? $input['REQUEST_METHOD'] : '');
        $this->request->setProtocol(isset($input['SERVER_PROTOCOL']) ? $input['SERVER_PROTOCOL'] : '');

        if (isset($input['QUERY_STRING']) && strlen($input['QUERY_STRING']) > 0) {
            $this->request->setQueryString(isset($input['QUERY_STRING']) ? $input['QUERY_STRING'] : '');
        }

        if (isset($input['HTTPS']) && $input['HTTPS'] == 'on') {
            $this->request->setScheme('https');
            $this->request->setSecure(true);
        }
        else {
            $this->request->setScheme('http');
        }

        $this->request->setContextPath($contextPath);
    }
    
    private function parseHeaders(& $headers) {
        $this->request->setHeaders($headers);
        $this->request->setInput($_POST);
    }
}
?>

<?php
abstract class Form {
    public function setValue($key, $val) {
        if(property_exists($this, 'form')) {
            $this->form[$key] = $val;
        }
    }
    
    public function getValue($key) {
        if(property_exists($this, 'form') && isset($this->form[$key])) {
            return $this->form[$key];
        }
        return null;
    }
    
    public function setData($data = array()) {
        if(!empty($data) && property_exists($this, 'form')) {
            foreach($data as $key => $val) {
                if(isset($this->form[$key])) {
                    $this->form[$key] = $val;
                }
            }
        }
    }
    
    public function getData() {
        if(property_exists($this, 'form')) {
            return $this->form;
        }
        return array();
    }
    
    public function clear() {
        if(property_exists($this, 'form') && !empty($this->form)) {
            foreach($this->form as $key => $val) {
                $this->form[$key] = '';
            }
        }
    }

    public function validate() {
        return true;
    }

    public function addError($error) {
        if(!property_exists($this, 'errors')) {
            $this->errors = array();
        }
        $this->errors[] = $error;
    }

    public function getError() {
        if(property_exists($this, 'errors') && isset($this->errors[0])) {
            $last = count($this->errors);
            if($last > 0) $last --;
            return $this->errors[$last];
        }
        else {
            return '';
        }
    }

    public function getErrors() {
        if(!property_exists($this, 'errors')) {
            return $this->errors;
        }
        else {
            return array();
        }
    }
}
?>

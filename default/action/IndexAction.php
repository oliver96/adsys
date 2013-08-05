<?php
import("action.CommonAction");

class IndexAction extends CommonAction {
    public function index() {
        /**
        $postData   = $this->request->getInput();
        $userModel  = new UserModel();
        $userRow = $userModel->getOne(array('id' => 1));
        if($userRow) {
            $userDetail->set('name', '');
            if(-1 == $userDetail->save()) {
                echo $userModel->getError();
            }
        }
        
        $userModel->insert(array(
            'name'      => 'rabbah',
            'password'  => '123456'
        ));
        */
        
        $this->output();
    }
}

?>

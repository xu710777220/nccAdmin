<?php

namespace Admin\Common;
use Think\Controller;

class BaseController extends Controller{
    public function __construct()
    {
        parent::__construct();
        $this->checkLogin();
    }

    /**
     * @author xu
     * @content 判断登录
     */
    public function checkLogin(){
        $user_name  = $_SESSION['username'];
        if(empty($user_name)){
            redirect('/Login/login');
        }
    }
}

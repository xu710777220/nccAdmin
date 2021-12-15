<?php
namespace Admin\Controller;

use Think\Controller;

class LoginController extends Controller{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @author xujian
     * @content 登录页面
     * @date 2021年12月10日17:01:52
     */
    public function login(){
        $this->display('');
    }

    /**
     * @author xujian
     * content 验证登录
     * @date 2021年12月10日17:28:00
     */
    public function checkLogin(){
        if(IS_AJAX){
            $username = $_POST['username'];
            $password = md5($_POST['password']);
            //验证账户密码是否合法
            $mes = M('t_user','','HIS_DB')->where("login_name = '$username' AND login_pwd = '$password'")->find();
            if($mes){
                //存取session
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                $_SESSION['nickname'] = $mes['user_name'];
                ToolJson('1','登录成功');
            }else{
                ToolJson('2','登录账号或密码错误');

            }
        }else{
            ToolJson('2','非法登录方式');
        }
    }

    /**
     * @author xujian
     * @content 退出登录
     * @date 2021年12月10日21:10:21
     */
    public function logOut(){
        session_destroy();
        ToolJson('1','退出成功!');
    }
}
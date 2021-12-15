<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
use Think\Controller;
class IndexController extends BaseController
{
    /**
     * @author xujian
     * @content 首页
     * @date 2021年12月10日18:06:25
     */
    public function index()
    {
        $this->display();
    }

    public function notFound(){
        $this->display('not_found');
    }
}
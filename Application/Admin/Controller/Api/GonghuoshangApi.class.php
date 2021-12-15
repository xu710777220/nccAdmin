<?php
namespace Admin\Api\Controller;
use Think\Controller;

class GonghuoshangApiController extends Controller{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $code = $_REQUEST['code'];
        if(empty($code)){
            ToolJson(2,'缺少必备参数');
            exit;
        }
        $data = json_decode($_REQUEST['data'],true);

    }

}
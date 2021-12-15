<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
use Think\Controller;
class DrugController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @author xujian
     * @content 药品对照
     * @date 2021年12月10日21:09:33
     */
    public function index(){
        $this->display();
    }

    /**
     * @author xujian
     * @content 获取his列表数据
     * @date 2021年12月10日21:39:58
     */
    public function getHisList(){
        if(IS_AJAX){
            $page = $_GET['page'] ? $_GET['page'] : 1;
            $limit = $_GET['limit'] ? $_GET['limit'] : 10;
            $his_code = isset($_GET['hisword']) ? $_GET['hisword'] : "";
            $is_dui = isset($_GET['is_dui']) ? $_GET['is_dui'] : "";
            //获取总条数
            $condition = "";
            if(!empty($his_code)){
                $condition.= " AND (drug_name LIKE '%$his_code%' OR abbreviate_name LIKE '%$his_code%')";
            }
            if(!empty($is_dui)){
                $condition.=" AND is_dui = $is_dui";
            }
            $total_list = M('t_drug','','HIS_DB')->where("is_del = 0 AND is_show = 1 AND is_dui = 1 $condition")->select();
            $count = count($total_list);
            $offset = ceil($page - 1) * $limit;
            $list = M('t_drug td','','HIS_DB')
                    ->field('drug_id,ins_code,drug_name,drug_chemical_name,drug_form_name,drug_specification,tdp.drug_producer_name,src_unit_name,is_dui,abbreviate_name')
                    ->join('t_drug_form tdf ON td.drug_form_id = tdf.drug_form_id')
                    ->join('t_drug_producer tdp ON td.drug_producer_id = tdp.drug_producer_id')
                    ->join('t_src_unit tsu ON td.src_unit_id = tsu.src_unit_id')
                    ->where("td.is_del = 0 AND is_show = 1 and is_dui = 1 $condition")
                    ->limit($page,$limit)
                    ->order('drug_id','DESC')
                    ->select();
            ToolJson('0','',$list,$count);
        }
    }


    /**
     * @author xujian
     * @content 获取ncc列表数据
     * @date 2021年12月11日13:23:30
     */
    public function getNccList(){
        if(IS_AJAX){
            $page = $_GET['page'] ? $_GET['page'] : 1;
            $limit = $_GET['limit'] ? $_GET['limit'] : 10;
            $his_code = isset($_GET['nccword']) ? $_GET['nccword'] : "";
            $is_dui = isset($_GET['is_dui']) ? $_GET['is_dui'] : "";
            //获取总条数
            $condition = "";
            if(!empty($his_code)){
                $condition.= " AND (name LIKE '%$his_code%' OR code LIKE '%$his_code%')";
            }
            if(!empty($is_dui)){
                $condition.=" AND is_dui = $is_dui";
            }
            $total_list = M('ncc_wuliao')->where("enablestate = 2 AND dr = 0 AND is_liwai = 1 $condition")->select();
            $count = count($total_list);
            $offset = ceil($page - 1) * $limit;
            $list = M('ncc_wuliao')
                ->where("enablestate = 2 AND dr = 0 AND is_liwai = 1 $condition")
                ->limit($offset,$limit)
                ->order('id','DESC')
                ->select();
            ToolJson('0','',$list,$count);
        }
    }

    /**
     * @author xujian
     * @content 药品项目对照
     * @date 2021年12月11日21:17:05
     */
    public function upDrugDuizhao(){
        $ncc_codes  = $_POST['ncc_codes'];
        $his_ids = $_POST['his_ids'];

        //组合数据
        $add_arr = array();
        //查询HIS药品信息
        $data = M('t_drug td','','HIS_DB')
            ->field('drug_id,ins_code,drug_name,drug_chemical_name,drug_form_name,drug_specification,tdp.drug_producer_name,src_unit_name,abbreviate_name')
            ->join('t_drug_form tdf ON td.drug_form_id = tdf.drug_form_id')
            ->join('t_drug_producer tdp ON td.drug_producer_id = tdp.drug_producer_id')
            ->join('t_src_unit tsu ON td.src_unit_id = tsu.src_unit_id')
            ->where("td.drug_id IN($his_ids)")
            ->select();
        foreach($data as $k => $v){
            $data[$k]['wuliao_code'] = $ncc_codes;
            $data[$k]['user_name'] = $_SESSION['username'].'||'.$_SESSION['nickname'];
            $data[$k]['create_time'] = date('Y-m-d H:i:s',time());
        }
        //添加入库
        M('drug_his_ncc')->addAll($data);

        //更改HIS对照状态
        $up_data['is_dui'] =  2;
        M('t_drug','','HIS_DB')->where("drug_id IN($his_ids)")->save($up_data);
        //更改NCC对照状态
        M('ncc_wuliao')->where("code = '$ncc_codes'")->save($up_data);

        ToolJson('0','操作成功');
    }

    /**
     * @author xujian
     * @content 药品对照记录
     * @date 2021年12月11日22:00:10
     */
    public function drugList(){
        if(IS_AJAX){
            $page = $_GET['page'] ? $_GET['page'] : 1;
            $limit = $_GET['limit'] ? $_GET['limit'] : 10;
            $wuliao_code = isset($_GET['wuliao_code']) ? $_GET['wuliao_code'] : "";
            $hisword = isset($_GET['hisword']) ? $_GET['hisword'] : "";
            $condition = "1= 1";
            if(!empty($wuliao_code)){
                $condition.= " AND wuliao_code LIKE '%$wuliao_code%'";
            }
            if(!empty($hisword)){
                $condition.= " AND (drug_name LIKE '%$hisword%' OR abbreviate_name LIKE '%$hisword%')";

            }
            //查询总条数
            $total_list = M('drug_his_ncc')->where("$condition")->select();
            $count = count($total_list);
            $offset = ceil($page - 1) * $limit;
            $list =  M('drug_his_ncc')
                ->field('drug_his_ncc.id,drug_id,ins_code,drug_name,drug_chemical_name,drug_form_name,drug_specification,drug_producer_name,src_unit_name,abbreviate_name,name,wuliao_code,user_name,create_time')
                ->join('ncc_wuliao ON drug_his_ncc.wuliao_code = ncc_wuliao.code')
                ->where("$condition")
                ->limit($offset,$limit)
                ->select();
            ToolJson('0','',$list,$count);
        }else{
            $this->display('drug_list');
        }
    }

    /**
     * @author xujian
     * @content 删除对照
     * @date 2021年12月11日23:05:36
     */
    public function delDrug(){
        $id = $_GET['id'];
        $data = M('drug_his_ncc')->where("id = $id")->find();
        $drug_id = $data['drug_id'];
        $wuliao_code = $data['wuliao_code'];
        //删除对照表记录
        M('drug_his_ncc')->where("id = $id")->delete();
        //更改HIS表对照状态
        $up_arr['is_dui'] = 1;
        M('t_drug','','HIS_DB')->where("drug_id = $drug_id")->save($up_arr);
        //更改NCC表对照状态
        $up_res = M('drug_his_ncc')->where("wuliao_code = '$wuliao_code'")->select();
        if(empty($up_res)){
            M('ncc_wuliao')->where("code = '$wuliao_code'")->save($up_arr);
        }

        ToolJson('0','操作成功');
    }

    /**
     * @author xujian
     * @content 例外列表
     * @date 2021年12月15日15:54:03
     */
    public function upLiwai(){
        if(IS_AJAX){
            $ncc_codes = $_REQUEST['ncc_codes'];
            $data = explode(',',$ncc_codes);
            $ncc_code_ids = "";
            foreach($data as $v){
                $ncc_code_ids .= "'".$v."',";
            }
            $newstr = substr($ncc_code_ids,0,strlen($ncc_code_ids)-1);
            $up_data['is_liwai'] = 2;
            $res = M('ncc_wuliao')->where("code IN ($newstr)")->save($up_data);
            if($res){
                ToolJson(0,'操作成功');
            }else{
                ToolJson(2,'操作失败');
            }
        }
    }

    /**
     * @author xujian
     * @content 例外列表
     * @date 2021年12月15日16:08:52
     */
    public function liwaiList(){
        if(IS_AJAX){
            $page = $_GET['page'] ? $_GET['page'] : 1;
            $limit = $_GET['limit'] ? $_GET['limit'] : 10;
            $his_code = isset($_GET['nccword']) ? $_GET['nccword'] : "";
            //获取总条数
            $condition = "";
            if(!empty($his_code)){
                $condition.= " AND (name LIKE '%$his_code%' OR code LIKE '%$his_code%')";
            }
            $total_list = M('ncc_wuliao')->where("enablestate = 2 AND dr = 0 AND is_liwai = 2 $condition")->select();
            $count = count($total_list);
            $offset = ceil($page - 1) * $limit;
            $list = M('ncc_wuliao')
                ->where("enablestate = 2 AND dr = 0 AND is_liwai = 2 $condition")
                ->limit($offset,$limit)
                ->order('id','DESC')
                ->select();
            ToolJson('0','',$list,$count);
        }else{
            $this->display('liwai_list');
        }
    }

    /**
     * @author xujian
     * @content 例外列表
     * @date 2021年12月15日15:54:03
     */
    public function quxiaoLiwai(){
        if(IS_AJAX){
            $ncc_codes = $_REQUEST['ncc_codes'];
            $data = explode(',',$ncc_codes);
            $ncc_code_ids = "";
            foreach($data as $v){
                $ncc_code_ids .= "'".$v."',";
            }
            $newstr = substr($ncc_code_ids,0,strlen($ncc_code_ids)-1);
            $up_data['is_liwai'] = 1;
            $res = M('ncc_wuliao')->where("code IN ($newstr)")->save($up_data);
            if($res){
                ToolJson(0,'操作成功');
            }else{
                ToolJson(2,'操作失败');
            }
        }
    }
}



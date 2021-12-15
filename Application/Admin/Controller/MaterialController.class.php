<?php
namespace Admin\Controller;
use Admin\Common\BaseController;
use Think\Controller;
class MaterialController extends BaseController
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
                $condition.= " AND (material_name LIKE '%$his_code%' OR abbreviate_name LIKE '%$his_code%')";
            }
            if(!empty($is_dui)){
                $condition.=" AND is_dui = $is_dui";
            }
            $total_list = M('t_material','','HIS_DB')->where("is_del = 0 AND is_show = 1 AND is_dui = 1 $condition")->select();
            $count = count($total_list);
            $offset = ceil($page - 1) * $limit;
            $list = M('t_material td','','HIS_DB')
                ->field('material_id,ins_code,material_name,material_chemical_name,material_form_name,material_specification,tdp.material_producer_name,src_unit_name,is_dui,abbreviate_name')
                ->join('t_material_form tdf ON td.material_form_id = tdf.material_form_id')
                ->join('t_material_producer tdp ON td.material_producer_id = tdp.material_producer_id')
                ->join('t_src_unit tsu ON td.src_unit_id = tsu.src_unit_id')
                ->where("td.is_del = 0 AND is_show = 1 and is_dui = 1 $condition")
                ->limit($page,$limit)
                ->order('material_id','DESC')
                ->select();
            ToolJson('0','',$list,$count);
        }
    }

    /**
     * @author xujian
     * @content 药品项目对照
     * @date 2021年12月11日21:17:05
     */
    public function upMaterialDuizhao(){
        $ncc_codes  = $_POST['ncc_codes'];
        $his_ids = $_POST['his_ids'];

        //组合数据
        $add_arr = array();
        //查询HIS药品信息
        $data = M('t_material td','','HIS_DB')
            ->field('material_id,ins_code,material_name,material_chemical_name,material_form_name,material_specification,tdp.material_producer_name,src_unit_name,abbreviate_name')
            ->join('t_material_form tdf ON td.material_form_id = tdf.material_form_id')
            ->join('t_material_producer tdp ON td.material_producer_id = tdp.material_producer_id')
            ->join('t_src_unit tsu ON td.src_unit_id = tsu.src_unit_id')
            ->where("td.material_id IN($his_ids)")
            ->select();
        foreach($data as $k => $v){
            $data[$k]['wuliao_code'] = $ncc_codes;
            $data[$k]['user_name'] = $_SESSION['username'].'||'.$_SESSION['nickname'];
            $data[$k]['create_time'] = date('Y-m-d H:i:s',time());
        }
        //添加入库
        M('material_his_ncc')->addAll($data);

        //更改HIS对照状态
        $up_data['is_dui'] =  2;
        M('t_material','','HIS_DB')->where("material_id IN($his_ids)")->save($up_data);
        //更改NCC对照状态
        M('ncc_wuliao')->where("code = '$ncc_codes'")->save($up_data);

        ToolJson('0','操作成功');
    }

    /**
     * @author xujian
     * @content 药品对照记录
     * @date 2021年12月11日22:00:10
     */
    public function materialList(){
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
                $condition.= " AND (material_name LIKE '%$hisword%' OR abbreviate_name LIKE '%$hisword%')";

            }
            //查询总条数
            $total_list = M('material_his_ncc')->where("$condition")->select();
            $count = count($total_list);
            $offset = ceil($page - 1) * $limit;
            $list =  M('material_his_ncc')
                ->field('material_his_ncc.id,material_id,ins_code,material_name,material_chemical_name,material_form_name,material_specification,material_producer_name,src_unit_name,abbreviate_name,name,wuliao_code,user_name,create_time')
                ->join('ncc_wuliao ON material_his_ncc.wuliao_code = ncc_wuliao.code')
                ->where("$condition")
                ->limit($offset,$limit)
                ->select();
            ToolJson('0','',$list,$count);
        }else{
            $this->display('material_list');
        }
    }

    /**
     * @author xujian
     * @content 删除对照
     * @date 2021年12月11日23:05:36
     */
    public function delMaterial(){
        $id = $_GET['id'];
        $data = M('material_his_ncc')->where("id = $id")->find();
        $material_id = $data['material_id'];
        $wuliao_code = $data['wuliao_code'];
        //删除对照表记录
        M('material_his_ncc')->where("id = $id")->delete();
        //更改HIS表对照状态
        $up_arr['is_dui'] = 1;
        M('t_material','','HIS_DB')->where("material_id = $material_id")->save($up_arr);
        //更改NCC表对照状态
        $up_res = M('material_his_ncc')->where("wuliao_code = '$wuliao_code'")->select();
        if(empty($up_res)){
            M('ncc_wuliao')->where("code = '$wuliao_code'")->save($up_arr);
        }

        ToolJson('0','操作成功');
    }
}



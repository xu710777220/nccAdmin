<?php
namespace Admin\Model;
use Think\Model;
class IndexModel extends Model {

    public function getHisDrug(){
        $data = M('his_drug')->select();
        //$data = M('his_drug')->WHERE('id_del = 0')->LIMIT('10')->order('drug_id','DESC')->select();
        var_dump($data);
    }
}

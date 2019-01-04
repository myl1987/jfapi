<?php
namespace app\index\model;

use think\Model;
use think\Db;


/*
*用户数据
*/

class Yuezhaninfo  extends Model{

	protected $pk = 'yz_id';
    protected $table = 'b_yuezhaninfo';
    protected function initialize()
    {
        parent::initialize();
    }

    public function add($data)
    {
        $rst=db('yuezhaninfo')->insert($data);
        
        return $rst;
    }

    public function getAll()
    {
        $tmp=[
            'yz_province'=>"北京市",
            'yz_shi'=>"市辖区",
            'yz_city'=>"东城区",
            'yz_status'=>0
        ];
        $rst=db('yuezhaninfo')->where($tmp)->select();
        if ($rst) {
            return  $rst;
        }else{
            return  0;
        }
    	
    }

    public function getByid($id)
    {
 
        $rst=db('yuezhaninfo')->where('yz_id',$id)->find();
        if ($rst) {
            return  $rst;
        }else{
            return  0;
        }
    }


}
<?php
namespace app\index\model;

use think\Model;
use think\Db;
use think\Session;


/*
*用户数据
*/

class Admin extends Model{
 
    protected $pk = 'admin_id';
    protected $table = 'p_admin';

    protected function initialize()
    {
        parent::initialize();
    }

    public function login($name,$pwd)
    {
        $tmp=[
                'admin_name'=>$name,
                'admin_pwd'=>$pwd
            ];
       
        $data=db('admin')->field('admin_role,admin_token')->where($tmp)->find();

        if ($data) {
            $rst=[
                "data"=>$data,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
             
        }else{
            $rst=[
                "data"=>-1,
                "code"=>20000,
                "msg"=>'账号或者密码错误，请重新输入！'
            ];
           
        }
         return $rst;

    }

    public function getUserinfoBytoken($token)
    {

        $data=db('admin')->where('admin_token',$token)->find();
        if ($data) {
            $rst=[
                "data"=>$data,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
             
        }else{
            $rst=[
                "data"=>-1,
                "code"=>20001,
                "msg"=>'获取失败！'
            ];
           
        }
         return $rst;


    }

    public function getAll(){
        $data=db('admin')
              ->alias('a')
              ->leftJoin('p_yuanxi b', 'b.yx_code=a.admin_yuanxiID')
              ->leftJoin('p_role c','c.r_code=a.admin_role')
              ->select();
        if ($data) {
          return $data;
             
        }else{
           return-1;
           
        }
      
    }
    public function add($data)
    {
 
        $rst=db('admin')->insert($data);
        

        if ($rst) {
            return  $rst;
        }else{
            return  -1;
        }
    }

    public function deleteByid($id)
    {
        $rst=db('admin')->where('admin_id',$id)->delete();

        if ($rst) {
            return  $rst;
        }else{
            return  -1;
        }  
    }


    public function isHas($name,$yxid)
    {
        $rst=db('admin')->where('admin_name',$name)->where('admin_yuanxiID',$yxid)->find();
        if ($rst) {
            return  true;
        }else{
            return  false;
        } 
    }

    public function edit($data,$id)
    {
        $rst=db('admin')->where('admin_id',$id)->data($data)->update();
        

        if ($rst) {
            return  $rst;
        }else{
            return  -1;
        }
    }

    public function resetPwd($id)
    {
        $rst=db('admin')->where('admin_id',$id)->update(['admin_pwd' => '123456']);
        

        if ($rst) {
            return  $rst;
        }else{
            return  -1;
        }
    }

    public function editpwd($old,$new)
    {
        $rst=db('admin')->where('admin_pwd',$old)
        ->where('admin_token',session('token'))
        ->where('admin_name',session('username'))->find();

        if($rst){
            $tmp=db('admin')->where('admin_token',session('token'))
            ->where('admin_name',session('username'))->update(['admin_pwd' =>$new]);
            if ($tmp) {
                $rst=2;
            }else{
                $rst=3;
            }
            

        }else{
            $rst=1;
        }

         return  $rst;


    }

    public function setManagerID($mid,$uid)
    {
        $rst=db('admin')->where('admin_id',$uid)->update(['admin_role' =>$mid]);
        if ($rst) {
            return  $rst;
        }else{
            return  -1;
        }
    }


}
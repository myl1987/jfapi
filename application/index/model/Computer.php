<?php
namespace app\index\model;

use think\Model;
use think\Db;
use think\Exception;
 

class Computer  extends Model{

	protected $pk = 'c_id';

    protected function initialize()
    {
        parent::initialize();
    }

    public function addAll($data)
    {
        try{

           $rst=db('computer')->insertAll($data);
           if ($rst) {
                return $rst;
            }else{
                return  0;
            }
        }catch(Exception $e){

            return $e->getMessage();
        }
    }

    public function getAll($sxsid)
    {

        $rst=db('computer')->where('c_sxsid',$sxsid)->order('c_code asc')->select();
        if ($rst) {
            return  $rst;
        }else{
            return  0;
        }
    	
    }
    public function getNameByCode($code)
    {

        $rst=db('yuanxi')->field('yx_name')->where('yx_code',$code)->find();
        if ($rst) {
            return  $rst;
        }else{
            return  0;
        }
        
    }

    public function add($data)
    {
 
        $rst=db('computer')->insert($data);
        

        if ($rst) {
            return  $rst;
        }else{
            return  -1;
        }
    }

    public function edit($data,$id)
    {
        $rst=db('computer')->where('c_id',$id)->data($data)->update();
        

        if ($rst) {
            return  $rst;
        }else{
            return  -1;
        }
    }

    public function deleteByid($id)
    {

        $rst=db('computer')->where('c_id',$id)->delete();

        if ($rst) {
            return  $rst;
        }else{
            return  -1;
        }  
    }

}
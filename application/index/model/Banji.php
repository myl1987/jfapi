<?php
namespace app\index\model;

use think\Model;
use think\Db;

 

class Banji extends Model{
 
    protected $pk = 'bj_id';
    

    protected function initialize()
    {
        parent::initialize();
    }

   
    public function getAll($zyid){

        $data=db('banji')
              ->alias('a')
              ->leftJoin('p_yuanxi b', 'b.yx_code=a.bj_yxid')
              ->leftJoin('p_zhuanye c','c.zy_id=a.bj_zyid')
              ->where('bj_zyid',$zyid)
              ->select();
        if ($data) {
          return $data;
             
        }else{
           return-1;
           
        }
      
    }
 
    public function getByyear($year,$yxid){

        $data=db('banji')
              ->alias('a')
              ->distinct(true)
              ->field('zy_name,zy_id')
              ->leftJoin('p_zhuanye c','c.zy_id=a.bj_zyid')
              ->where('bj_year',$year)
              ->where('bj_yxid',$yxid)
              ->select();
        if ($data) {
          return $data;
             
        }else{
           return 0;
           
        }
      
    }

    public function getByzy($year,$zyid){

        $data=db('banji')
              ->field('bj_name,bj_id')
              ->where('bj_year',$year)
              ->where('bj_zyid',$zyid)
              ->select();
        if ($data) {
          return $data;
             
        }else{
           return-1;
           
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

}
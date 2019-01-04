<?php
namespace app\index\model;

use think\Model;
use think\Db;
use think\Session;

/*
*用户数据
*/

class Shixunshi  extends Model{

	protected $pk = 'sxs_id';
    protected $table = 'p_shixunshi';
    protected function initialize()
    {
        parent::initialize();
    }

    public function add($data)
    {
 
        $rst=db('shixunshi')->insert($data);
        

        if ($rst) {
           // $tmp=$this->getAll();
            return  $rst;
        }else{
            return  -1;
        }
    }

    public function getAll()
    {
        if (session('role')=='1') {
            $rst=db('shixunshi')              
             ->alias('a')
             ->join('p_admin b', 'b.admin_id=a.sxs_managerid')
             ->where('sxs_managerid',session('adminid'))  ->select();
        }else{
            $rst=db('shixunshi')   
              ->alias('a') 
              ->leftjoin('p_admin b', 'b.admin_id=a.sxs_managerid')          
              ->select();
        }

        if ($rst) {
            return  $rst;
        }else{
            return  0;
        }
    	
    }

    public function getInfoByid($id)
    {
        $rst=db('shixunshi')->where('sxs_id',$id)->find();
        if ($rst) {
            return  $rst;
        }else{
            return  0;
        }
    }

    public function getsxsPk($zhouci,$jieci)
    {
        if ($jieci=='12') {
            $jieci=['1','2'];
        }
        if ($jieci=='34') {
            $jieci=['3','4'];
        }        
        $rst=Db::table('p_shixunshi')
            ->where('sxs_id','NOT IN',function($query) use ($zhouci,$jieci){
                $query->table('p_kecheng')->where('kc_day','eq',$zhouci)
                ->where('kc_jieci','in',$jieci)
                ->field('kc_sxsid');
            })->select();


        if ($rst) {
            return  $rst;
        }else{
            return  0;
        }
        
    }
    
    public function deleteByid($id)
    {

        $tmp=db('computer')->where('c_sxsid',$id)->find();
        if ($tmp) {
           return  -1;
        }else{
          $rst=db('shixunshi')->where('sxs_id',$id)->delete(); 
            if ($rst) {
                return  $rst;
            }else{
                return  0;
            }   
        }
        

       
    }

    public function getroom($bname)
    {
        $rst=db('shixunshi')->field('sxs_name')->where('sxs_building',$bname)->select();

        if ($rst) {
            return  $rst;
        }else{
            return  -1;
        } 
    }
    public function getbuilding()
    {
        $rst=db('shixunshi')->distinct(true)->field('sxs_building')->select();

        if ($rst) {
            return  $rst;
        }else{
            return  -1;
        } 
    }

    public function edit($data,$id)
    {
        $rst=db('shixunshi')->where('sxs_id',$id)->data($data)->update();
        

        if ($rst) {
            return  $rst;
        }else{
            return  -1;
        }
    }
}
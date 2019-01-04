<?php
namespace app\index\model;

use think\Model;
use think\Db;


/*
*用户数据
*/

class Xueqi  extends Model{

	protected $pk = 'xq_id';
    protected $table = 'p_xueqi';
    protected function initialize()
    {
        parent::initialize();
    }

    public function add($data)
    {
        $rst=db('xueqi')->insert($data);
        
        if ($rst) {
            return  $rst;
        }else{
            return  0;
        }
    }

    public function getAll()
    {

        $rst=db('xueqi')->select();
        if ($rst) {
            return  $rst;
        }else{
            return  0;
        }
    	
    }

    public function deleteByid($id)
    {
        $rst=db('xueqi')->where('xq_id',$id)->delete();

        if ($rst) {
            return  $rst;
        }else{
            return  -1;
        }  
    }

    public function isHas($year,$term)
    {
        $rst=db('xueqi')->where('xq_year',$year)->where('xq_term',$term)->find();
        if ($rst) {
            return  true;
        }else{
            return  false;
        } 
    }

    public function edit($data,$id)
    {
        $rst=db('xueqi')->where('xq_id',$id)->data($data)->update();
        

        if ($rst) {
            return  $rst;
        }else{
            return  -1;
        }
    }

    public function stratTerm($id,$flag)
    {
        if ($flag) {

            $rst=db('xueqi')->where('xq_id',$id)->update(['xq_status' =>'0']);

        }else{
            $rst=db('xueqi')->where('xq_status',1)->select();
            if ($rst) {
                return 2; 
            }else{
                $rst=db('xueqi')->where('xq_id',$id)->update(['xq_status' =>'1']);
            }
            
        }

       

        if ($rst) {
            return  $rst;
        }else{
            return  0;
        }
    }


}
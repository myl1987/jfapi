<?php
namespace app\index\model;

use think\Model;
use think\Db;

 

class Yuanxi  extends Model{

	protected $pk = 'yx_id';
    protected function initialize()
    {
        parent::initialize();
    }

    public function add($data)
    {
        $rst=db('yuanxi')->insert($data);
        
        if ($rst) {
            return  $rst;
        }else{
            return  0;
        }
    }

    public function getAll()
    {

        $rst=db('yuanxi')->select();
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

}
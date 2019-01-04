<?php
namespace app\index\model;

use think\Model;
use think\Db;


/*
*用户数据
*/

class Building  extends Model{

	protected $pk = 'b_id';
    protected $table = 'p_building';
    protected function initialize()
    {
        parent::initialize();
    }

    public function add($data)
    {
        $rst=db('building')->insert($data);
        
        if ($rst) {
            return  $rst;
        }else{
            return  0;
        }
    }

    public function getAll()
    {

        $rst=db('building')->select();
        if ($rst) {
            return  $rst;
        }else{
            return  0;
        }
    	
    }


}
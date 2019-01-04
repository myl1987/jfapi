<?php
namespace app\index\model;

use think\Model;
use think\Db;


/*
*用户数据
*/

class Soft  extends Model{

	protected $pk = 's_id';
    protected $table = 'p_soft';
    protected function initialize()
    {
        parent::initialize();
    }

    public function add($data)
    {
        $rst=db('soft')->insert($data);
        
        return $rst;
    }

    public function getAll()
    {

        $rst=db('soft')->select();
        if ($rst) {
            return  $rst;
        }else{
            return  0;
        }
    	
    }


}
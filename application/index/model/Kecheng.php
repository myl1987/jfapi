<?php
namespace app\index\model;

use think\Model;
use think\Db;
use think\Session;

 
class Kecheng  extends Model{

	protected $pk = 'kc_id';
    protected $table = 'p_kecheng';
    protected function initialize()
    {
        parent::initialize();
    }

    public function add($data)
    {
        
        if ($data['kc_jieci']>4) {
            switch ($data['kc_jieci']) {
                case '12':
                    $data['kc_jieci']=1;
                    $rst=db('kecheng')->insert($data);
                    $data['kc_jieci']=2;
                    $rst=db('kecheng')->insert($data);
                    break;
                case '34':
                    $data['kc_jieci']=3;
                    $rst=db('kecheng')->insert($data);
                    $data['kc_jieci']=4;
                    $rst=db('kecheng')->insert($data);                    
                    break;                
                default:
                    # code...
                    break;
            }
        }else{
            $rst=db('kecheng')->insert($data);
        }

        

        if ($rst) {
            return  $rst;
        }else{
            return  -1;
        }
    }

    public function getAll()
    {
        if (session('role')==0) {
                    $rst=db('kecheng')     
                    ->alias('a')
                    ->leftJoin('p_banji f', 'f.bj_id=a.kc_class')
                    ->leftJoin('p_shixunshi e', 'e.sxs_id=a.kc_sxsid')
                    ->leftJoin('p_xueqi b', 'b.xq_id=a.kc_xqid')
                    ->leftJoin('p_yuanxi c', 'c.yx_code=a.kc_yxid')
                    ->leftJoin('p_zhuanye d', 'd.zy_id=a.kc_zyid')
                    ->where('kc_xqid',session('xqid'))
             ->select();
        }else{
                    $rst=db('kecheng')
                     ->alias('a')
                    ->leftJoin('p_shixunshi e', 'e.sxs_id=a.kc_sxsid')
                    ->leftJoin('p_banji f', 'f.bj_id=a.kc_class')
                    ->leftJoin('p_xueqi b', 'b.xq_id=a.kc_xqid')
                    ->leftJoin('p_yuanxi c', 'c.yx_code=a.kc_yxid')
                    ->leftJoin('p_zhuanye d', 'd.zy_id=a.kc_zyid')
                    ->where('kc_xqid',session('xqid'))
                    ->where('kc_yxid',session('yxid'))->select();
        }

        if ($rst) {
            return  $rst;
        }else{
            return  0;
        }
    	
    }

    public function getsxsID($zhouci,$jieci )
    {
        $rst=db('kecheng')
        ->field('kc_sxsid')
         ->where('kc_week',$zhouci)
         ->where('kc_jieci','in',$jieci)
        ->select();
        
        if ($rst) {
            return  $rst;
        }else{
            return  0;
        }

    }

    public function getkb($yxcode,$year,$class,$zhuanye)
    {
        $rst=db('kecheng')
         ->alias('a')
        ->leftJoin('p_shixunshi e', 'e.sxs_id=a.kc_sxsid')
        ->leftJoin('p_banji f', 'f.bj_id=a.kc_class')
        ->leftJoin('p_yuanxi c', 'c.yx_code=a.kc_yxid')
        ->leftJoin('p_zhuanye d', 'd.zy_id=a.kc_zyid')
        ->where('kc_xqid',session('xqid'))
 
         ->where('kc_year',$year)
         ->where('kc_class',$class)
         ->where('kc_zyid',$zhuanye)
         ->where('kc_yxid',$yxcode)
        ->select();

        $tableData_yx=array();

        if ($rst) {
            for ($i=0; $i <4; $i++) { 
        
               $arr=[];
               $arr['jieci']='第'.($i+1).'大节';
               $arr['w1']='';
               $arr['w2']='';
               $arr['w3']='';
               $arr['w4']='';
               $arr['w5']='';
               $week='';
               for ($j=0; $j <count($rst) ; $j++) { 
                    if ($rst[$j]['kc_jieci']==($i+1)) {
                        
                        if ($rst[$j]['kc_weeks']==null)
                        {
                            $week="<br>【周次】：每周重复";
                        }else{
                            $week= "<br>【周次】:".$rst[$j]['kc_weeks'];
                        }

                        $arr['w'.$rst[$j]['kc_day']]=
                        $rst[$j]['kc_name'].'<br>【机房】:'.$rst[$j]['sxs_building'].$rst[$j]['sxs_room'].
                        $rst[$j]['sxs_name'].$week;
                    }
               

               }
               array_push($tableData_yx, $arr);
            }
            return  $tableData_yx;
        }else{
            return  0;
        }
    }

    public function getkb_sxs($building,$name)
    {
        $sxsid=db('shixunshi')->field('sxs_id')->where('sxs_building',$building)
             ->where('sxs_name',$name)->find();


        // if (session('role')==0) {
            $rst=db('kecheng')
                 ->alias('a')
                ->leftJoin('p_shixunshi e', 'e.sxs_id=a.kc_sxsid')
                ->leftJoin('p_banji f', 'f.bj_id=a.kc_class')
                ->leftJoin('p_yuanxi c', 'c.yx_code=a.kc_yxid')
                ->leftJoin('p_zhuanye d', 'd.zy_id=a.kc_zyid')
                ->where('kc_xqid',session('xqid'))
                ->where('kc_sxsid',$sxsid['sxs_id'])
                ->order('kc_day asc,kc_jieci asc')
                ->select();        

        // }else{
        //     $rst=db('kecheng')
        //          ->alias('a')
        //         ->leftJoin('p_shixunshi e', 'e.sxs_id=a.kc_sxsid')
        //         ->leftJoin('p_banji f', 'f.bj_id=a.kc_class')
        //         ->leftJoin('p_yuanxi c', 'c.yx_code=a.kc_yxid')
        //         ->leftJoin('p_zhuanye d', 'd.zy_id=a.kc_zyid')
        //         ->where('kc_xqid',session('xqid'))
        //         ->where('kc_sxsid',$sxsid['sxs_id'])
        //         ->where('kc_yxid',session('yxid'))
        //         ->order('kc_day asc,kc_jieci asc')
        //         ->select();
        // }


        

        if ($rst) {
           
            return  $rst;
        }else{
            return  0;
        } 
    }

    public function deleteByid($kcid)
    {
        $rst=db('kecheng')->where('kc_id',$kcid)->delete();

        if ($rst) {
            return  $rst;
        }else{
            return  -1;
        }  
    }

    public function edit($data,$id)
    {
        $rst=db('kecheng')->where('kc_id',$id)->data($data)->update();
        

        if ($rst) {
            return  $rst;
        }else{
            return  -1;
        }
    }

}
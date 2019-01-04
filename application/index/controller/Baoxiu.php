<?php
namespace app\index\controller;
use think\Controller;
 
use think\Db;
use think\Session;
 

class Baoxiu extends Common
{

	public function getbxInfo()
	{
		$subsql =db('computer')
			->alias('a')
            ->leftJoin('p_shixunshi b', 'b.sxs_id=a.c_sxsid')
			->field('sxs_name,c_id,c_code,c_flag')
			->buildSql();

		$tmp=db('baoxiu')
		 	->alias('a')
            ->leftJoin('p_admin b', 'b.admin_id=a.bx_bxrid')
            ->leftJoin([$subsql=> 'w'],'a.bx_sbid=w.c_id')
            ->where('bx_bxrid',session('adminid'))
            ->where('bx_flag',0)
            ->select();
        if ($tmp>=0) {
            $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else{
             $rst=[
                "data"=>-1,
                "code"=>20000,
                "msg"=>'获取失败！'
            ];
        }
        
        return json($rst);
	}

    public function getbxInfo_end()
    {
        $subsql =db('computer')
            ->alias('a')
            ->leftJoin('p_shixunshi b', 'b.sxs_id=a.c_sxsid')
            ->field('sxs_name,c_id,c_code,c_flag')
            ->buildSql();

        $tmp=db('baoxiu')
            ->alias('a')
            ->leftJoin('p_admin b', 'b.admin_id=a.bx_bxrid')
            ->leftJoin([$subsql=> 'w'],'a.bx_sbid=w.c_id')
            ->where('bx_bxrid',session('adminid'))
            ->where('bx_flag','neq',0)
            ->select();
        if ($tmp>=0) {
            $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else{
             $rst=[
                "data"=>-1,
                "code"=>20000,
                "msg"=>'获取失败！'
            ];
        }
        
        return json($rst);
    }


    public function getMyEvents()
    {
        $subsql =db('computer')
            ->alias('a')
            ->leftJoin('p_shixunshi b', 'b.sxs_id=a.c_sxsid')
            ->field('sxs_name,c_id,c_code,c_flag')
            ->buildSql();

        $tmp=db('baoxiu')
            ->alias('a')
            ->leftJoin('p_admin b', 'b.admin_id=a.bx_bxrid')
            ->leftJoin([$subsql=> 'w'],'a.bx_sbid=w.c_id')
            ->where('bx_shrid',session('adminid'))
            ->where('c_flag','in',['1','3'])
            ->where('bx_flag',0)
            ->select();
        if ($tmp>=0) {
            $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else{
             $rst=[
                "data"=>-1,
                "code"=>20000,
                "msg"=>'获取失败！'
            ];
        }
        
        return json($rst);
    }


	public function addbx()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        $cid=input('id');
        $data=array(
            'bx_sbid' => $cid,
            'bx_peijian' => $tmp['data']['peijianCode'], 
            'bx_kcid' => $tmp['data']['kucunID'], 
            'bx_shrid' => $tmp['data']['shenheren'], 
            'bx_bxrid' =>session('adminid'), 
            'bx_content' => $tmp['data']['miaoshu'], 
            'bx_time' => time(),
            'bx_flag' => 0,
        
        );
        


        Db::startTrans();
        try{
            $bxid=db('baoxiu')->insertGetId($data);
            $tmp1=db('computer')->where('c_id',$cid)->setField('c_flag', '1');

            //jian ku cun
            $kcid = explode(",", $tmp['data']['kucunID']);
            for ($i=0; $i <count($kcid); $i++) { 

                $kcdata=array(
                    'kcsy_kcid' => $kcid[$i],
                    'kcsy_bxid' => $bxid, 
                    'kcsy_user' => session('adminid'), 
                    'kcsy_time' => time(),
                ); 
                db('kucunshiyong')->insert($kcdata); //使用明细
                db('kucun')->where('kc_id',$kcid[$i])->setDec('kc_num');         //减库存 
            }
            ///

            if ($tmp1) {
                

                $rst=[
                    "data"=>$tmp1,
                    "code"=>20000,
                    "msg"=>'获取成功！'
                ];
            }else{
                 $rst=[
                    "data"=>-1,
                    "code"=>20000,
                    "msg"=>'获取失败！'
                ];
            }
            Db::commit();
        }catch (\Exception $e) {
            
            Db::rollback();
        }

        
        return json($rst);        
    }

    public function deletebxByid()
    {

        $id=input('id');
        $sbid=input('sbid');
        
        $tmp1= db('computer')->where('c_id',$sbid)->update(['c_flag' => '0']);

        //撤回申请  库存+
        $tmpdata =db('baoxiu')->where('bx_id',$id)->value('bx_kcid'); 
        $kcid=explode(",", $tmpdata);
        for ($i=0; $i <count($kcid); $i++) { 
 
            db('kucun')->where('kc_id',$kcid[$i])->setInc('kc_num');        //撤回申请  库存+
        }
        /////
        $tmp= db('baoxiu')->where('bx_id',$id)->delete();
  
        $tmp2= db('kucunshiyong')->where('kcsy_bxid',$id)->delete();  //撤回申请  删除明细使用
        if ($tmp) {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'成功！'
            ];
        }else{
             $rst=[
                "data"=>0,
                "code"=>20000,
                "msg"=>'失败！'
            ];
        }
        return json($rst);
    }

    public function chuli()
    {
        $bxid=input('id');
        $sbid=input('sbid');
        $yijian=input('yijian');
        $content=input('content');

        switch ($yijian) {
            case '0'://不同意
                $tmp1= db('computer')->where('c_id',$sbid)->update(['c_flag' => '0']);
                $tmp1= db('baoxiu')->where('bx_id',$bxid)->update(['bx_flag' => '2']);
                $tmp2= db('kucunshiyong')->where('kcsy_bxid',$bxid)->delete();  //撤回申请  删除明细使用
                //不同意  库存+
                $tmpdata =db('baoxiu')->where('bx_id',$bxid)->value('bx_kcid'); 
                $kcid=explode(",", $tmpdata);
                for ($i=0; $i <count($kcid); $i++) { 
         
                    db('kucun')->where('kc_id',$kcid[$i])->setInc('kc_num');  //撤回申请  库存+
                }

                break;
            case '1'://同意
                $tmp1= db('computer')->where('c_id',$sbid)->update(['c_flag' => '2']);
                break;
            case '2':
                $tmp1= db('computer')->where('c_id',$sbid)->update(['c_flag' => '0']);
                $tmp1= db('baoxiu')->where('bx_id',$bxid)->update(['bx_flag' => '1']);
                break;            
            default:
                 
                break;
        }


  
        $data=array(
                    'bxmx_bxid' => $bxid,
                    'bxmx_clrid' => session("adminid"), 
                    'bxmx_yjid'=>$yijian,
                    'bxmx_clyj' => $content, 
                    'bxmx_time' => time()
                );

        $tmp=db('baoxiumingxi')->insert($data);
        if ($tmp) {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'成功！'
            ];
        }else{
             $rst=[
                "data"=>-1,
                "code"=>20000,
                "msg"=>'失败！'
            ];
        }
        return json($rst);
    }

    public function chuli_fqr()
    {
        $bxid=input('id');
        $sbid=input('sbid');
   
        $content=input('content');
    

        $tmp1= db('computer')->where('c_id',$sbid)->update(['c_flag' => '3']);

        $data=array(
                    'bxmx_bxid' => $bxid,
                    'bxmx_clrid' => session("adminid"), 
                    'bxmx_yjid'=>2,      //2表示发起人已处理完成
                    'bxmx_clyj' => $content, 
                    'bxmx_time' => time()
                );

        $tmp=db('baoxiumingxi')->insert($data);
        if ($tmp) {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'成功！'
            ];
        }else{
             $rst=[
                "data"=>-1,
                "code"=>20000,
                "msg"=>'失败！'
            ];
        }
        return json($rst);
    }

    public function getBxDetails()
    {
        $bxid=input('id');


        // $subsql =db('computer')
        //     ->alias('a')
        //     ->leftJoin('p_shixunshi b', 'b.sxs_id=a.c_sxsid')
        //     ->field('sxs_name,c_id,c_code,c_flag')
        //     ->buildSql();

        // $tmp=db('baoxiu')
        //     ->alias('a')
        //     ->leftJoin('p_admin b', 'b.admin_id=a.bx_bxrid')
        //     ->leftJoin([$subsql=> 'w'],'a.bx_sbid=w.c_id')
        //     ->where('bx_shrid',session('adminid'))
        //     ->where('c_flag','in',['1','3'])
        //     ->where('bx_flag',0)
        //     ->select();
        $tmp=db('baoxiumingxi')
            ->alias('a')
            ->leftJoin('p_baoxiu b','b.bx_id=a.bxmx_bxid')
            ->leftJoin('p_admin c','c.admin_id=a.bxmx_clrid')
            ->where('bxmx_bxid',$bxid)
            ->order('bxmx_time asc')
            ->select();


        if ($tmp) {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'成功！'
            ];
        }else{
             $rst=[
                "data"=>-1,
                "code"=>20000,
                "msg"=>'失败！'
            ];
        }
        return json($rst);
    }


}
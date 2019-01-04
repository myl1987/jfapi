<?php
namespace app\index\controller;
use think\Controller;
 
use think\Db;
use think\Session;
 

class Kucun extends Common
{

    public function getpjInfo()
    {

        $tmp=db('peijian')->order('pj_id asc')->select();
        if($tmp) 
        {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else
        {
             $rst=[
                "data"=>-1,
                "code"=>20000,
                "msg"=>'获取失败！'
            ];
        }
        return json($rst);        
    }

    public function getPeijianAll()
    {
        $flag=input('flag');
        if ($flag) {   //全部
            $tmp=db('kucun')
                ->alias('a')
                ->leftJoin('p_peijian b', 'b.pj_code=a.kc_pjid')->order('kc_id asc')->select();            
        }else{  //库存不为0
            $tmp=db('kucun')
                ->alias('a')
                ->leftJoin('p_peijian b', 'b.pj_code=a.kc_pjid')
                ->where('kc_num','neq','0')
                ->order('kc_id asc')->select();
        }

        if($tmp>=0) 
        {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else
        {
             $rst=[
                "data"=>-1,
                "code"=>20000,
                "msg"=>'获取失败！'
            ];
        }
        return json($rst);        
    }
    public function addpj()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        
        $data=array(
            'kc_pjid' => $tmp['data']['pjid'], 
            'kc_name' => $tmp['data']['pjname'], 
            'kc_pinpai' => $tmp['data']['pinpai'], 
            'kc_guige' => $tmp['data']['guige'], 
            'kc_num' => $tmp['data']['num'],
            'kc_gongyingshang' =>$tmp['data']['gongyingshang'],
            'kc_jiage' => $tmp['data']['jiage'],
            'kc_creattime' => time()
        );

        $tmp1=db('kucun')->insert($data);
        
        if ($tmp1) {
            $rst=[
                "data"=>$tmp1,
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

    public function editpj()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        $id=input('id');
        $data=array(
            'kc_pjid' => $tmp['data']['pjid'], 
            'kc_name' => $tmp['data']['pjname'], 
            'kc_pinpai' => $tmp['data']['pinpai'], 
            'kc_guige' => $tmp['data']['guige'], 
            'kc_num' => $tmp['data']['num'],
            'kc_gongyingshang' =>$tmp['data']['gongyingshang'],
            'kc_jiage' =>$tmp['data']['jiage'],
        );
  
        $tmp1=db('kucun')->where('kc_id',$id)->data($data)->update();
        
        if ($tmp1) {
            $rst=[
                "data"=>$tmp1,
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

    public function deletePjByid()
    {
        $id=input('id');
        
        $tmp= db('kucun')->where('kc_id',$id)->delete();
        if ($tmp) {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'删除成功'
            ];
        }else{
             $rst=[
                "data"=>0,
                "code"=>20000,
                "msg"=>'删除失败'
            ];
        }
        return json($rst);
    }

    public function findpjBycode()
    {
        $code=input('code');

        if ($code=='') {
            $tmp=db('kucun')
            ->alias('a')
            ->leftJoin('p_peijian b', 'b.pj_code=a.kc_pjid')->order('kc_id asc')->select();
        }else{
             $tmp=db('kucun')
            ->alias('a')
            ->leftJoin('p_peijian b', 'b.pj_code=a.kc_pjid')
            ->where('kc_pjid',$code)->order('kc_id asc')->select();
        }

       
        if($tmp>=0) 
        {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else
        {
             $rst=[
                "data"=>-1,
                "code"=>20000,
                "msg"=>'获取失败！'
            ];
        }
        return json($rst);        
    }

    public function addPjNum()
    {
        $id=input('id');
        $num=input('num');
        $tmp=db('kucun')->where('kc_id',$id)->setInc('kc_num', $num);
       
        $data=array(
            'kcsy_kcid' => $id, 
            'kcsy_flag' => 1, 
            'kcsy_num' => $num, 
            'kcsy_user' =>session('adminid'), 
            'kcsy_time' =>time()
          
        );
        $tmp1=db('kucunshiyong')->insert($data);
        
        if ($tmp1) {
            $rst=[
                "data"=>$tmp1,
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

    public function lookhistory()
    {
        $kcid=input('id');

         

        $tmp1=db('kucunshiyong')
            ->alias('a')
            ->leftJoin('p_admin w','a.kcsy_user=w.admin_id')
            ->where('kcsy_kcid',$kcid)->order('kcsy_time asc')->select();
        
        if ($tmp1) {
            $rst=[
                "data"=>$tmp1,
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
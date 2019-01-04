<?php
namespace app\index\controller;
use app\index\model\Admin as Admin;
use app\index\model\Building as Building;
use app\index\model\Soft as Soft;
use app\index\model\Shixunshi as Shixunshi;
use app\index\model\Kecheng as Kecheng;
use app\index\model\Xueqi as Xueqi;
use app\index\model\Yuanxi as Yuanxi;
use app\index\model\Banji as Banji;
use app\index\model\Computer as Computer;

use think\Controller;
 
use think\Db;
use think\Session;
 
 

class Index extends Common
{
	public function index()
    {

        dump(session('token'));
 
	}

    public function login(){

        $name=input('username');
        $pwd=input('password');
 

        $admin=new Admin();

        $rst=$admin->login($name,$pwd);
        

        return json($rst);
    }

    public function logout(){
        $rst=[
                "data"=>-1,
                "code"=>20000,
                "msg"=>'退出'
            ];
           
        return json($rst);
    }

    public function userinfo(){

        $token=input('token');
        $admin=new Admin();

        $rst=$admin->getUserinfoBytoken($token);
        

        return json($rst);
    }


 

    public function addsxs()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        
        $data=array(
            'sxs_name' => $tmp['sxsdata']['name'], 
            'sxs_building' => $tmp['sxsdata']['building'], 
            'sxs_room' => $tmp['sxsdata']['room'],
            'sxs_capacity' => $tmp['sxsdata']['capacity'],
            'sxs_soft' => json_encode($tmp['sxsdata']['soft']), 
            'sxs_managerid' => $tmp['sxsdata']['managerID'], 
            'sxs_content' => $tmp['sxsdata']['content']
        );
  
        $sxs=new Shixunshi();
         
        $tmp1= $sxs->add($data);
        
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
        
        return json($rst);
    }

    public function editsxs()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        $id=input('id');
        $data=array(
            'sxs_name' => $tmp['sxsdata']['name'], 
            'sxs_building' => $tmp['sxsdata']['building'], 
            'sxs_room' => $tmp['sxsdata']['room'],
            'sxs_capacity' => $tmp['sxsdata']['capacity'],
            'sxs_soft' => json_encode($tmp['sxsdata']['soft']), 
            'sxs_managerid' => $tmp['sxsdata']['managerID'], 
            'sxs_content' => $tmp['sxsdata']['content']
        );
  
        $sxs=new Shixunshi();
         
        $tmp1= $sxs->edit($data,$id);
        
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
        
        return json($rst);
    }


    public function getAllsxs()
    {
        $sxs=new Shixunshi;
        $tmp= $sxs->getAll();
        if ($tmp) {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else{
             $rst=[
                "data"=>0,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }
        return json($rst);
         
    }

    public function getsxsPk()
    {
 
        $data = json_decode(file_get_contents("php://input"), true);

        //获取本节已使用的实训室id
     
        $sxs=new Shixunshi;
       
        $tmp= $sxs->getsxsPk($data['data']['zhouci'],$data['data']['jieci']);
       


        if ($tmp) {
            $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else{
             $rst=[
                "data"=>0,
                "code"=>20000,
                "msg"=>'无返回数据！'
            ];
        }
        return json($rst);
    }

    public function getbuilding()
    {
        $building=new Building;
        $tmp= $building->getAll();
        if ($tmp) {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else{
             $rst=[
                "data"=>0,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }
        return json($rst);
         
    }

    public function getsoft()
    {
        $soft=new Soft;
        $tmp= $soft->getAll();
        if ($tmp) {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else{
             $rst=[
                "data"=>0,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }
        return json($rst);
         
    } 

    public function addkecheng()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        
        if ($tmp['kcdata']['status']=='是') {
            $weeks=null;
        }else{
            $weeks=json_encode($tmp['kcdata']['weeks']);
        }
        $data=array(
              
            'kc_xqid' => session('xqid'),
            'kc_yxid' => session('yxid'),
            'kc_year' => $tmp['kcdata']['year'], 
            'kc_zyid' => $tmp['kcdata']['zhuanye'], 
            'kc_class' => $tmp['kcdata']['class'], 
            'kc_sxsid' => $tmp['kcdata']['sxs'], 
            'kc_name' => $tmp['kcdata']['name'], 
            'kc_teacher' => $tmp['kcdata']['teacher'],
            'kc_day' => $tmp['kcdata']['zhouci'], 
            'kc_jieci' => $tmp['kcdata']['jieci'], 
            'kc_status' => $tmp['kcdata']['status'], 
            'kc_weeks' =>$weeks, 
            'kc_creattime' => time(),
       
        );
 
  
        $kc=new Kecheng();
         
        $tmp1= $kc->add($data);
        
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
        
        return json($rst);
    }

    public function editkecheng()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        $kcid=input('id');

        if ($tmp['kcdata']['status']=='是') {
            $weeks=null;
        }else{
            $weeks=json_encode($tmp['kcdata']['weeks']);
        }
        $data=array(
              
            'kc_year' => $tmp['kcdata']['year'], 
            'kc_zyid' => $tmp['kcdata']['zhuanye'], 
            'kc_class' => $tmp['kcdata']['class'], 
            'kc_sxsid' => $tmp['kcdata']['sxs'], 
            'kc_name' => $tmp['kcdata']['name'], 
            'kc_teacher' => $tmp['kcdata']['teacher'],
            'kc_day' => $tmp['kcdata']['zhouci'], 
            'kc_jieci' => $tmp['kcdata']['jieci'], 
            'kc_status' => $tmp['kcdata']['status'], 
            'kc_weeks' =>$weeks, 
            'kc_creattime' => time(),
       
        );
 
  
        $kc=new Kecheng();
         
        $tmp1= $kc->edit($data,$kcid);
        
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
        
        return json($rst);
    }


    public function getAllkc()
    {
        $kc=new Kecheng;
        $tmp= $kc->getAll();
        if ($tmp) {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else{
             $rst=[
                "data"=>0,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }
        return json($rst);
         
    }

    public function deletekbByid()
    {
        $kcid=input('kcid');
        $kc=new Kecheng;
        $tmp= $kc->deleteByid($kcid);
        if ($tmp) {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else{
             $rst=[
                "data"=>0,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }
        return json($rst);

    }

    public function getAllxq()
    {
        $xq=new Xueqi;
        $tmp= $xq->getAll();
        if ($tmp) {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else{
             $rst=[
                "data"=>0,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }
        return json($rst);
    }

    public function addXq()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        
        
        $data=array(
            'xq_year' => $tmp['data']['year'], 
            'xq_term' => $tmp['data']['term'], 
            'xq_start' => $tmp['data']['start'],
            'xq_end' => $tmp['data']['end'],
            'xq_week' =>ceil(($tmp['data']['end']-$tmp['data']['start'])/604800000),
            'xq_creattime' => time(),

        );
 
  
        $xq=new Xueqi();
        $has=$xq->ishas($tmp['data']['year'],$tmp['data']['term']);
        if ($has) {
             $rst=[
                    "data"=>-1,
                    "code"=>20000,
                    "msg"=>'存在相同学年、学期，请检查！'
                ];
        }else{
            $tmp1= $xq->add($data);
            
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
        }
        return json($rst);
    }

    public function stratTerm()
    {
    	$id=input('id');
    	$flag=input('flag');
    	$xq=new Xueqi();

    	$tmp1= $xq->stratTerm($id,$flag);
        
        if ($tmp1) {
            $rst=[
                "data"=>$tmp1,
                "code"=>20000,
                "msg"=>'修改成功！'
            ];
        }else{
             $rst=[
                "data"=>-1,
                "code"=>20000,
                "msg"=>'修改失败！'
            ];
        }
     
        return json($rst);
    }

    public function editXq()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        $id=input('id');
        $xq=new Xueqi();
         
        $data=array(
            'xq_year' => $tmp['data']['year'], 
            'xq_term' => $tmp['data']['term'], 
            'xq_start' => $tmp['data']['start'],
            'xq_end' => $tmp['data']['end'],
            'xq_week' =>ceil(($tmp['data']['end']-$tmp['data']['start'])/604800000),
            'xq_creattime' => time(),

        );
      
        $tmp1= $xq->edit($data,$id);
        
        if ($tmp1) {
            $rst=[
                "data"=>$tmp1,
                "code"=>20000,
                "msg"=>'修改成功！'
            ];
        }else{
             $rst=[
                "data"=>-1,
                "code"=>20000,
                "msg"=>'修改失败！'
            ];
        }
     
        return json($rst);
    }



    public function getAllUser()
    {
        $user=new Admin;
        $tmp= $user->getAll();
        if ($tmp) {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else{
             $rst=[
                "data"=>0,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }
        return json($rst);
    }

    public function deleteUserByid()
    {
        $id=input('id');
        $user=new Admin;
        $tmp= $user->deleteByid($id);
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

    public function resetUserPwd()
    {
        $id=input('id');
        $user=new Admin;
        $tmp= $user->resetPwd($id);
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


    public function addUser()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        $user=new Admin();
        $has=$user->ishas($tmp['data']['account'],$tmp['data']['yuanxi']);

        if (!$has) {
            $data=array(
                'admin_yuanxiID' => $tmp['data']['yuanxi'], 
                'admin_name' => $tmp['data']['account'], 
                'admin_role' => $tmp['data']['role'],
             
                'admin_start' =>(isset($tmp['data']['start'])?$tmp['data']['start']:'0'),
                'admin_end' => (isset($tmp['data']['end'])?$tmp['data']['end']:'0'),
          
                'admin_username' => $tmp['data']['username'],
                'admin_phone' => (isset($tmp['data']['phone'])?$tmp['data']['phone']:''),
        
                'admin_time' => time(),
                'admin_status' =>0,
                'admin_pwd' =>'123456',
                'admin_token' =>md5($tmp['data']['account']),


            );
     
            $tmp1= $user->add($data);
            
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
        }else{
            $rst=[
                    "data"=>-1,
                    "code"=>20000,
                    "msg"=>'已存在此用户，请检查！'
            ];
        }
 
        return json($rst);
    }

    public function editUser()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        $id=input('id');
        $user=new Admin();
       

        $data=array(
            'admin_yuanxiID' => $tmp['data']['yuanxi'], 
            'admin_name' => $tmp['data']['account'], 
            'admin_role' => $tmp['data']['role'],
            'admin_start' => $tmp['data']['start'],
            'admin_end' => $tmp['data']['end'],
            'admin_username' => $tmp['data']['username'],
            'admin_phone' => $tmp['data']['phone'],
            'admin_time' => time(),
            'admin_status' =>0,
        );
 
        $tmp1= $user->edit($data,$id);
        
        if ($tmp1) {
            $rst=[
                "data"=>$tmp1,
                "code"=>20000,
                "msg"=>'更新成功！'
            ];
        }else{
             $rst=[
                "data"=>-1,
                "code"=>20000,
                "msg"=>'更新失败！'
            ];
        }            
         
 
        return json($rst);        
    }

    public function setManagerID()
    {
        $mid=input('mid');
        $uid=input('uid');
        $user=new Admin();

        $tmp= $user->setManagerID($mid,$uid);
            
        if ($tmp) {
            $rst=[
                "data"=>1,
                "code"=>20000,
                "msg"=>'设置成功！'
            ];
        }else{
             $rst=[
                "data"=>0,
                "code"=>20000,
                "msg"=>'设置失败！'
            ];
        }            
        return json($rst);

    }
    public function getYuanxi()
    {
        $yx=new Yuanxi;
        $tmp= $yx->getAll();
        if ($tmp) {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else{
             $rst=[
                "data"=>0,
                "code"=>20000,
                "msg"=>'获取失败！'
            ];
        }
        return json($rst);
    }

    public function deleteXqByid()
    {
        $id=input('id');
        $xq=new Xueqi;
        $tmp= $xq->deleteByid($id);
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

    public function deleteSxsByid()
    {
        $id=input('id');
        $sxs=new Shixunshi;
        $tmp= $sxs->deleteByid($id);

        switch ($tmp) {
            case 0:
                $rst=[
                    "data"=>$tmp,
                    "code"=>20000,
                    "msg"=>'删除失败！'
                ];
                break;
            case -1:
                $rst=[
                    "data"=>$tmp,
                    "code"=>20000,
                    "msg"=>'该机房下有设备未删除，请先删除设备！'
                ]; 
                break;

            default:
                $rst=[
                    "data"=>$tmp,
                    "code"=>20000,
                    "msg"=>'删除成功'
                ];
                break;
        }

 
        return json($rst);
    }

    public function addzy()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        
        
        $data=array(
            'zy_name' => $tmp['data']['name'], 
            'zy_yxcode' =>$tmp['data']['yxid'], 
        );
 
        $tmp1=db('zhuanye')->insert($data);
         
        
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
        
        return json($rst);
    }

    public function editzy()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        $zyid=input('id');
        
        $data=array(
            'zy_name' => $tmp['data']['name'], 
        );
        $tmp1=db('zhuanye')->where('zy_id',$zyid)->data($data)->update();
 
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
        
        return json($rst);
    }

    public function deleteZyByid()
    {
        $zyid=input('id');

        $tmp1=db('zhuanye')->where('zy_id',$zyid)->delete();
        $tmp2=db('banji')->where('bj_zyid',$zyid)->delete();
        $tmp3=db('kecheng')->where('kc_zyid',$zyid)->delete();
         
        if ($tmp1) {
             $rst=[
                "data"=>1,
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

    public function deleteBjByid()
    {
        $bjid=input('id');


        $tmp1=db('banji')->where('bj_id',$bjid)->delete();
        $tmp2=db('kecheng')->where('kc_class',$bjid)->delete();
         
        if ($tmp1) {
             $rst=[
                "data"=>1,
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

    public function getAllzy()
    {
        if (session('role')!=4) {
           $tmp= db('zhuanye')->leftjoin('p_yuanxi','p_yuanxi.yx_code=p_zhuanye.zy_yxcode')->select();
            
        }else{
            $tmp= db('zhuanye')->leftjoin('p_yuanxi','p_yuanxi.yx_code=p_zhuanye.zy_yxcode')->where('zy_yxcode',session('yxid'))->select();

        }
        if ($tmp) {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else{
             $rst=[
                "data"=>0,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }
        return json($rst);
    }

    public function addClass()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        
        $num=$tmp['data']['num'];
        $year=$tmp['data']['year'];
        $yxid=$tmp['data']['yxid'];
        $zyid=$tmp['data']['zyid'];
        
         
         for ($i=0; $i <$num ; $i++) { 
            $data=array(
                'bj_year' =>$year, 
                'bj_yxid' =>$yxid, 
                'bj_name' =>$i+1, 
                'bj_zyid' =>$zyid, 
            ); 
            $tmp1=db('banji')->insert($data);
         }
  
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
        
        return json($rst);
    }

    public function getAllclassData()
    {
        $zyid=input('zyid');
        $bj=new Banji;
        $tmp= $bj->getAll($zyid)
        ;
        if ($tmp) {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else{
             $rst=[
                "data"=>0,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }
        return json($rst);
    }

    public function getclassByyear()
    {
        $year=input('year');
        $yxcode=input('yxcode');

        if($yxcode=='editor'){
            $yxcode=session('yxid');
        }

        $bj=new Banji;
        $tmp= $bj->getByyear($year,$yxcode);
        if ($tmp) {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else{
             $rst=[
                "data"=>0,
                "code"=>20000,
                "msg"=>'获取失败！'
            ];
        }
        return json($rst);
    } 

    public function getclassByzy()
    {
        $year=input('year');
        $zyid=input('zyid');
        $bj=new Banji;
        $tmp= $bj->getByzy($year,$zyid)
        ;
        if ($tmp) {
             $rst=[
                "data"=>$tmp,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else{
             $rst=[
                "data"=>0,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }
        return json($rst);
    } 

    public function getWeeks()
    {
        $weeknum= db('xueqi')->field('xq_week,xq_id')->where('xq_status',1)->find();
        $weekarr=array();
        
        if ($weeknum) {
        	session('xqid',$weeknum['xq_id']);	

            for ($i=0; $i <$weeknum['xq_week']; $i++) { 
                array_push($weekarr,($i+1));
            }
             $rst=[
                "data"=>$weekarr,
                "code"=>20000,
                "msg"=>'获取成功！'
            ];
        }else{
             $rst=[
                "data"=>-1,
                "code"=>20000,
                "msg"=>'获取shibai！'
            ];
        }
        return json($rst);
    }
   
    public function getkb_yx()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        $year=$tmp['data']['year'];
        $class=$tmp['data']['class'];
        $zhuanye=$tmp['data']['zhuanye'];
        $yxcode=$tmp['data']['yuanxi'];

        $kc=new Kecheng;
        $tmp1=$kc->getkb($yxcode,$year,$class,$zhuanye);

         if($tmp1) 
         {
             $rst=[
                "data"=>$tmp1,
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


    public function getkb_sxs()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        $building=$tmp['data']['building'];
        $name=$tmp['data']['name'];
       

        $kc=new Kecheng;
        $tmp1=$kc->getkb_sxs($building,$name);

         if($tmp1) 
         {
             $rst=[
                "data"=>$tmp1,
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


    public function getroom()
    {
        $bname=input('bname');

        $sxs=new Shixunshi;
        $tmp1=$sxs->getroom($bname);
        if($tmp1) 
        {
             $rst=[
                "data"=>$tmp1,
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

    public function getbuilding_sxs()
    {
        

        $sxs=new Shixunshi;
        $tmp1=$sxs->getbuilding();
        if($tmp1) 
        {
             $rst=[
                "data"=>$tmp1,
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

    public function editPwd()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        $old=$tmp['data']['oldpwd'];
        $new=$tmp['data']['newpwd'];

        $user=new Admin;
        $tmp=$user->editpwd($old,$new);

        switch ($tmp) {
            case '1':
                $rst=[
                    "data"=>$tmp,
                    "code"=>20000,
                    "msg"=>'原密码错误！'
                ];
                break;
            case '2':
                $rst=[
                    "data"=>$tmp,
                    "code"=>20000,
                    "msg"=>'修改成功！'
                ];
                break;            
            case '3':
                $rst=[
                    "data"=>$tmp,
                    "code"=>20000,
                    "msg"=>'修改失败！'
                ];
                break;
            default:
                break;
        }
        return json($rst);
 
    }
    public function getYxNameByCode()
    {
        $code=input('code');
        $yx=new Yuanxi;

        if ($code==='true') {
            $tmp='管理员';
        }else{
            $tmp=$yx->getNameByCode( $code);

        }

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


    public function getExcelName()
    {
        $yx=input('yx');
        $zy=input('zy');
        $bj=input('bj');

        $yxname=db('yuanxi')->field('yx_name')->where('yx_code',$yx)->find();
        $zyname=db('zhuanye')->field('zy_name')->where('zy_id',$zy)->find();
        $bjname=db('banji')->field('bj_name')->where('bj_id',$bj)->find();

         $rst=[
                "data"=>$yxname['yx_name'].$zyname['zy_name'].$bjname['bj_name'].'班课程表',
                "code"=>20000,
                "msg"=>'获取成功！'
        ];
        return json($rst);
    }

    public function getsxsInfo()
    {
        $sxsid=input('id');

        $sxs=new Shixunshi;
        $tmp1=$sxs->getInfoByid($sxsid);
        if($tmp1) 
        {
             $rst=[
                "data"=>$tmp1,
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

    public function getshebeiInfo()
    {
        $sxsid=input('id');
        
        $c=new Computer;
        $tmp=$c->getAll($sxsid);
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

    public function excelUpload()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $sxsid=input('id');
        if (count($data['data'])==0) {
            $rst=[
                    "data"=>0,
                    "code"=>20000,
                    "msg"=>'导入数据表不能为空！'
            ];

            return json($rst);  
        }

        $c=new Computer;
        for ($i=0; $i <count($data['data']) ; $i++) { 
            $data['data'][$i]['c_sxsid']=$sxsid;
            $data['data'][$i]['c_flag']=0;
            $data['data'][$i]['c_creattime']=time();
        }
        $tmp=$c->addAll($data['data'] );


        if(intval($tmp)){
            if($tmp) 
            {
                $rst=[
                    "data"=>1,
                    "code"=>20000,
                    "msg"=>'导入成功，共导入'.$tmp.'条记录！'
                ];
            }else
            {
                 $rst=[
                    "data"=>0,
                    "code"=>20000,
                    "msg"=>'导入数据失败，请检查导入表内容！'
                ];
            }


        }else{
            $rst=[
                    "data"=>0,
                    "code"=>20000,
                    "msg"=>'导入失败,请检查表格格式是否与模板一致！错误信息：'.$tmp
            ];     
        }
        return json($rst);    
    }

    public function downExcel()
    {
        $rst=[
                "data"=>'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/api/pkapi/导入设备模板.xls',
                "code"=>20000,
                "msg"=>'下载成功！'
            ];
               
        return json($rst);    
         
    }

    public function addsb()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        
        $data=array(
            'c_sxsid' => $tmp['data']['sxsid'], 
            'c_code' => $tmp['data']['code'], 
            'c_cpu' => $tmp['data']['cpu'], 
            'c_neicun' => $tmp['data']['neicun'], 
            'c_yingpan' => $tmp['data']['yingpan'],
            'c_xianka' => $tmp['data']['xianka'],
            'c_jianpan' => $tmp['data']['jianpan'],
            'c_shubiao' => $tmp['data']['shubiao'],
            'c_xianshiqi' => $tmp['data']['xianshiqi'],
            'c_beizhu' => $tmp['data']['beizhu'],
            'c_creattime' => time(),
            'c_flag' => 0,
        
        );
  
        $c=new Computer();
         
        $tmp1= $c->add($data);
        
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
        
        return json($rst);
    }

    public function editsb()
    {
        $tmp = json_decode(file_get_contents("php://input"), true);
        $id=input('id');
        $data=array(
            'c_code' => $tmp['data']['code'], 
            'c_cpu' => $tmp['data']['cpu'], 
            'c_neicun' => $tmp['data']['neicun'], 
            'c_yingpan' => $tmp['data']['yingpan'],
            'c_xianka' => $tmp['data']['xianka'],
            'c_jianpan' => $tmp['data']['jianpan'],
            'c_shubiao' => $tmp['data']['shubiao'],
            'c_xianshiqi' => $tmp['data']['xianshiqi'],
            'c_beizhu' => $tmp['data']['beizhu'],
        );
  
        $c=new Computer();
         
        $tmp1= $c->edit($data,$id);
        
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
        
        return json($rst);
    }

    public function deleteSbByid()
    {
        $id=input('id');
        $c=new Computer;
        $tmp= $c->deleteByid($id);
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



    public function getshrInfo()
    {

        $tmp=db('admin')->where('admin_role','2')->order('admin_id asc')->select();
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



    public function getManagers()
    {

        $tmp=db('admin')->where('admin_role', '1')->order('admin_id asc')->select();
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
    

}

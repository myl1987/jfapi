<?php
namespace app\index\controller;
 

use think\Controller;
use think\Db;
use think\facade\Request;
use think\Session;
class Common extends Controller
{
	function initialize ()
    {
        $query=Request::query();
        

        if ($query!='s=/index/index/login') {
       		$info = Request::header(); 
       		$token=$info['x-token'];
       		if ($token) {
       			 $data=db('admin')->where('admin_token',$token)->find();
       			 if ($data) {
		             session('token', $data['admin_token']);
		             session('yxid', $data['admin_yuanxiID']);
		             session('role', $data['admin_role']);
		             session('username', $data['admin_name']);
		             session('adminid', $data['admin_id']);
	       
	             
		        }else{
		            $rst=[
		                "data"=>-1,
		                "code"=>20001,
		                "msg"=>'token错误，您无权访问！'
		            ];

		            json($rst)->send();
            		exit();
		           
		        }
       		}else{
		            $rst=[
		                "data"=>-1,
		                "code"=>20001,
		                "msg"=>'token错误，您无权访问！'
		            ];

		            json($rst)->send();
            		exit();
		           
		     }
        	
	        
        }
		
         

    }
}
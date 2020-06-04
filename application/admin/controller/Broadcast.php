<?php

namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\admin\model\Broadcast as BroadcastModel;
class Broadcast extends Controller{

	public function broadcast_list(){
		session_start();
	    $manage_id = is_manage();

	    if(!$manage_id){
	      alert('请登录','jump','admin/login');
	    }
	    $BroadcastModel = new BroadcastModel();
    	$broadcast = $BroadcastModel->getBroadcast($manage_id);

    	//var_dump($broadcast);die;
    	$this->assign('broadcast',$broadcast);
		return $this->fetch();
	}

	public function read(){
		session_start();
	    $manage_id = is_manage();

	    if(!$manage_id){
	      return false;
	    }
	    $id = input('id');

	    $BroadcastModel = new BroadcastModel();
    	return  $BroadcastModel->read($id);

	}

}
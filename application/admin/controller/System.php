<?php


namespace app\admin\controller;
use Redis;
use think\Controller;
use think\Db;
use app\admin\model\AdminLevel as AdminLevelModel;
use app\admin\model\Admin as AdminModel;
use app\admin\model\Broadcast as BroadcastModel;
class System extends Controller{
	public function system_base(){
		return $this->fetch();
	}
	public function system_data(){
		return $this->fetch();
	}
	public function system_log(){
		return $this->fetch();
	}
	public function system_shielding(){
		return $this->fetch();
	}

	public function system_broadcast(){
		$AdminLevelModel = new AdminLevelModel();
	    $manage_level = $AdminLevelModel->getAdminList();

	    $this->assign('manage_level',$manage_level);
		return $this->fetch();
	}

	public function broadcast_send(){
		session_start();
	    $manage_id = is_manage();

	    if(!$manage_id){
	      alert('请登录','jump','admin/login');
	    }

	    $data = input('post.');
	    if($data['title'] == ''){
	    	$this->error('标题不能为空');
	    }
	    if(empty($data['info'])){
	    	$this->error('内容不能为空');
	    }
	    $accept_id = [];
	    //处理数据
	    foreach ($data as $key => $value) {
	    	if(is_numeric($key) && is_numeric($value)){
	    		if($value == 1){
	    			$accept_id[]=$key;
	    		}
	    	}
	    }
	    if(empty($accept_id)){
	    	$this->error('请选择发送对象');
	    }
        //数据库操作
	    $BroadcastModel = new BroadcastModel();
	    $result = $BroadcastModel->sendBroadcast($manage_id,$accept_id,$data['title'],$data['info']);
	    //给在线管理员进行广播提示
        //开启redis
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        //寻找当前在线的管理员
        foreach ( $accept_id as $key => $value){
            $fd = $redis->get("uid_".$value);
            if($fd){
                //推送消息
                broadcast_websocket($fd);
            }
        }
        broadcast_websocket();

	   if($result){
	   		$this->success('发送成功!');
	   }else{
	   	$this->error('发送失败!');
	   }

	}
}


?>
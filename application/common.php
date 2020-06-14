<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Db;

	//0加密 1解密
 function encryption($value,$type=0){
	$key = config('encryption_key');
	if($type == 0){
		return str_replace('/','',base64_encode($value^$key));
	}else{
		return base64_decode($value)^ $key;
	}
}
	//获取当前用户权限
function getLevel($id){
	$level_arr = Db::table('yyhelper_admin')
	->alias('ya')
	->where('ya.id',$id)
	->join('yyhelper_admin_level yl','yl.id = ya.level_id')
	->field('user_audit,user_handle,user_look,user_del,manage_handle,manage_level_add,manage_look,news_edit,news_audit,news_look,news_del,route_look,route_audit,route_del')
	->find();

	if($level_arr){
		return $level_arr;
	}else{
		return false;
	}
}

	//验证管理员是否登录
function is_manage(){

	if(isset($_SESSION['manage']['username']) && isset($_SESSION['manage']['password']) ){
		$username = $_SESSION['manage']['username'];
		$password = $_SESSION['manage']['password'];
		$result = Db :: table('yyhelper_admin')->where('username', $username)->where('password', md5($password))->find();
		if($result){

			return $result['id'];
		}else{
			return false;
		}
	}else{
		return false;
	}
}
	/**
 * JS提示跳转
 * @param  $tip  弹窗口提示信息(为空没有提示)
 * @param  $type 设置类型 close = 关闭 ，back=返回 ，refresh=提示重载，jump提示并跳转url
 * @param  $url  跳转url
 * @param  $condition  跳转url传值
 */
function alert($tip = "", $type = "", $url = "",$condition = "") {
    $js = "<script>";
    if ($tip)
        $js .= "alert('" . $tip . "');";
    switch ($type) {
        case "close" : //关闭页面
            $js .= "window.close();";
            break;
        case "back" : //返回
            $js .= "history.back(-1);";
            break;
        case "refresh" : //刷新
            $js .= "parent.location.reload();";
            break;
        case "top" : //框架退出
            if ($url)
                $js .= "top.location.href='" . $url . "';";
            break;
        case "jump" : //跳转
			if ($url)
                $js .= "window.location.href='" . $url .  "';";
            if ($url && $condition)
                $js .= "window.location.href='" . $url . "?".$condition. "';";
			break;
        default :
            break;
    }
    $js .= "</script>";
    echo $js;
    if ($type) {
        exit();
    }
}

/**
 * JS发送websocket
 * @param array $fd
 * @param string $content
 */
function broadcast_websocket($fd = 0,$content = "您有新的信息"){
    if($fd == 0){
       return false;
    }
    $data =  '{"type":"broadcast","fd":"'.$fd.'","content":"'.$content.'"}';
    $data = addslashes($data);
    $js = "<script>";
    $js .=  "wss = new WebSocket('wss://www.yyhelper.icu/ws');";
    $js .= "wss.onopen = function (e) {wss.send('".$data."');}";
    $js .= "</script>";
    echo $js;
}
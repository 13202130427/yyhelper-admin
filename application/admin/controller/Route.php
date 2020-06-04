<?php


namespace app\admin\controller;
use think\Controller;
use app\admin\model\Route as RouteModel;
class Route extends Controller{
	public function route_list(){
		$RouteModel = new RouteModel();
    	$route = $RouteModel->getRouteList();
    	$count = $RouteModel->getRouteCount();


    	$this->assign('route',$route);
    	$this->assign('count',$count);
		return $this->fetch();
	}

	public function route_show(){
		$id = input('id');
		if(!isset($id) || !is_numeric($id)){
		return false;
		}
		$RouteModel = new RouteModel();
		$route = $RouteModel->getRouteInfo($id);
		//var_dump($route);die;
		$this->assign('data',$route);
		return $this->fetch();
	}

	public function route_audit_show(){
		$id = input('id');
		if(!isset($id) || !is_numeric($id)){
		return false;
		}
		$RouteModel = new RouteModel();
		$route = $RouteModel->getRouteInfo($id);
		//var_dump($route);die;
		$this->assign('data',$route);
		$this->assign('route_id',$id);
		return $this->fetch();
	}
	/**
	 * 路线审核
	 * status 审核状态 0未通过 1通过
	 * @return [type] [description]
	 */
	public function audit_action(){
		  session_start();
	      $manage_id = is_manage();

	      if(!$manage_id){
	        return false;
	      }
	      
	      if(!$level = getLevel($manage_id)){
	        return false;
	      }

	      // if($level['user_audit'] == 0){
	      //   alert('当前权限不够，请勿进行当前操作','close');
	      //  // $this->error('当前权限不够，请勿进行当前操作');
	      // }

	      $id=input('id');
	      $status = input('status');
	      if(!isset($id) || !is_numeric($id) ||!isset($status) || !is_numeric($status)){
	        return false;
	      }
	      if($status == 1){//审核通过
	      	$RouteModel = new RouteModel();
	        return $RouteModel->audit_handle($id,$manage_id,'1'); 
	      }
	      if($status == 0){//审核不通过
	        $cause = input('cause');
	        $RouteModel = new RouteModel();
	        return  $RouteModel->audit_handle($id,$manage_id,'0',$cause); 
	      }
	}

	public function route_del(){
		session_start();
		$manage_id = is_manage();

		if(!$manage_id){
			alert('请登录','jump','admin/login');
		}

		if(!$level = getLevel($manage_id)){
			alert('当前用户无权限角色','close');
		}

		// if($level['user_del'] == 0){
		// 	alert('当前权限不够，请勿进行当前操作','close');
		// // $this->error('当前权限不够，请勿进行当前操作');
		// }
		$id = input('id');
		if(!isset($id) || !is_numeric($id)){
			return false;
		}
		$RouteModel = new RouteModel();
		$result = $RouteModel->routeDel($id);
		return $result;
		}
	
	public function route_audit(){
		$RouteModel = new RouteModel();
    	$route = $RouteModel->getRouteAuditList();
    	$count = $RouteModel->getRouteAuditCount();

    	//var_dump($route);die;

    	$this->assign('route',$route);
    	$this->assign('count',$count);
		return $this->fetch();
	}
}


?>
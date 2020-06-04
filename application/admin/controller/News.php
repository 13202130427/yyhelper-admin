<?php


namespace app\admin\controller;
use think\Controller;
use app\admin\model\News as NewsModel;
use app\admin\model\Video as VideoModel;
use think\Db;

class News extends Controller{

	//新闻列表
	public function news_list(){
		session_start();
	    $manage_id = is_manage();

	    if(!$manage_id){
	      alert('请登录','jump','admin/login');
	    }
	    if(!$level = getLevel($manage_id)){
        	alert('当前用户无权限角色','close');
        }

	    if($level['news_look'] == 0){
	    	alert('当前权限不够，请勿进行当前操作','close');
	        // $this->error('当前权限不够，请勿进行当前操作');
	    }
	    $NewsModel = new NewsModel();
	    $data = $NewsModel->getNewsList();
	    $count = $NewsModel->getNewsCount();

	    $this->assign('data',$data);
	    $this->assign('count',$count);
		return $this->fetch();
	}
	//视频列表
	public function video_list(){
		session_start();
	    $manage_id = is_manage();

	    if(!$manage_id){
	      alert('请登录','jump','admin/login');
	    }
	    if(!$level = getLevel($manage_id)){
        	alert('当前用户无权限角色','close');
        }

	    if($level['news_look'] == 0){
	    	alert('当前权限不够，请勿进行当前操作','close');
	        // $this->error('当前权限不够，请勿进行当前操作');
	    }
	    $VideoModel = new VideoModel();
	    $data = $VideoModel->getVideoList();
	    $count = $VideoModel->getVideoCount();

	    $this->assign('data',$data);
	    $this->assign('count',$count);
		return $this->fetch();
	}
	//新闻删除
	public function news_del(){
		session_start();
	    $manage_id = is_manage();

	    if(!$manage_id){
	      alert('请登录','jump','admin/login');
	    }
	    if(!$level = getLevel($manage_id)){
        	alert('当前用户无权限角色','close');
        }

	    if($level['news_del'] == 0){
	    	return false;
	    	//alert('当前权限不够，请勿进行当前操作','close');
	        // $this->error('当前权限不够，请勿进行当前操作');
	    }

	    $id = input('id');
		  if(!isset($id) || !is_numeric($id)){
		    return false;
		  }
	    $NewsModel = new NewsModel();
	    return $NewsModel->newsDel($id);
	}
	//视频删除
	public function video_del(){
		session_start();
	    $manage_id = is_manage();

	    if(!$manage_id){
	      alert('请登录','jump','admin/login');
	    }
	    if(!$level = getLevel($manage_id)){
        	alert('当前用户无权限角色','close');
        }

	    if($level['news_del'] == 0){
	    	return false;
	    }

	    $id = input('id');
		  if(!isset($id) || !is_numeric($id)){
		    return false;
		  }
	    $VideoModel = new VideoModel();
	    return $VideoModel->videoDel($id);
	}
	//新闻编纂
	public function news_edit(){
		session_start();
	    $manage_id = is_manage();

	    if(!$manage_id){
	      alert('请登录','jump','admin/login');
	    }
	    if(!$level = getLevel($manage_id)){
        	alert('当前用户无权限角色','close');
        }

	    if($level['news_edit'] == 0){
	    	alert('当前权限不够，请勿进行当前操作','close');
	        //$this->error('当前权限不够，请勿进行当前操作');
	    }

		//删除status为-1的无作用新闻
		$news_del = Db::table('jyurec_sec_news')->where('status',-1)->delete();
		//判断草稿数量是否大于一
		$news_save_count = Db::table('jyurec_sec_news')->where('status',0)->where('admin_id',$manage_id)->count();
		if($news_save_count >= 1){
			//获取最后一次保存草稿的值
			$data = Db::table('jyurec_sec_news')->where('status',0)->limit(1)->where('admin_id',$manage_id)->order('id','desc')->find();
			$this->assign('data',$data);
			$this->assign('id',$data['id']);
		}else{
			//新增一条数据
			$data['status'] = -1;
			$data['first_news_id'] = 0;
			$data['admin_id'] = $manage_id;
			$id = Db::table('jyurec_sec_news')->insertGetId($data);
			$this->assign('id',$id);
		}
		
		
		return $this->fetch();
	}
	//新闻审查	
	public function news_audit(){
		session_start();
	    $manage_id = is_manage();

	    if(!$manage_id){
	      alert('请登录','jump','admin/login');
	    }
	    if(!$level = getLevel($manage_id)){
        	alert('当前用户无权限角色','close');
        }

	    if($level['news_audit'] == 0){
        	alert('当前权限不够，请勿进行当前操作','close');
	        //$this->error('当前权限不够，请勿进行当前操作');
	    }
	    $NewsModel = new NewsModel();
	    $data = $NewsModel->getNewsAuditList();
	    $count = $NewsModel->getNewsAuditCount();

	    $this->assign('data',$data);
	    $this->assign('count',$count);
		return $this->fetch();
	}
	//视频审查
	public function video_audit(){
		session_start();
	    $manage_id = is_manage();

	    if(!$manage_id){
	      alert('请登录','jump','admin/login');
	    }
	    if(!$level = getLevel($manage_id)){
        	alert('当前用户无权限角色','close');
        }

	    if($level['news_audit'] == 0){
        	alert('当前权限不够，请勿进行当前操作','close');
	        //$this->error('当前权限不够，请勿进行当前操作');
	    }
	    $VideoModel = new VideoModel();
	    $data = $VideoModel->getVideoAuditList();
	    $count = $VideoModel->getVideoAuditCount();

	    $this->assign('data',$data);
	    $this->assign('count',$count);
		return $this->fetch();
	}
	//视频审查界面
	public function video_audit_show(){
		session_start();
	    $manage_id = is_manage();

	    if(!$manage_id){
	      alert('请登录','jump','admin/login');
	    }
	    if(!$level = getLevel($manage_id)){
        	alert('当前用户无权限角色','close');
        }

	    if($level['news_audit'] == 0){
        	alert('当前权限不够，请勿进行当前操作','close');
	        //$this->error('当前权限不够，请勿进行当前操作');
	    }
		$id = input('id');
		if(!isset($id) || !is_numeric($id)){
		return false;
		}
		$VideoModel = new VideoModel();
		$video = $VideoModel->getVideoInfo($id);
		//var_dump($route);die;
		$this->assign('data',$video);
		$this->assign('video_id',$id);
		return $this->fetch();
	}

	//视频内容查看
	public function video_show(){
		session_start();
	    $manage_id = is_manage();

	    if(!$manage_id){
	      alert('请登录','jump','admin/login');
	    }
	    if(!$level = getLevel($manage_id)){
        	alert('当前用户无权限角色','close');
        }

	    if($level['news_look'] == 0){
	    	alert('当前权限不够，请勿进行当前操作','close');
	        // $this->error('当前权限不够，请勿进行当前操作');
	    }

		$id = input('id');
		if(!isset($id) || !is_numeric($id)){
			$this->error('参数传递错误');
		}
		$VideoModel = new VideoModel();
		$video = $VideoModel->getVideoInfo($id);
		$this->assign('data',$video);
		return $this->fetch();
	}
	//新闻撰写操作
	public function news_insert(){
		session_start();
	    $manage_id = is_manage();

	    if(!$manage_id){
	      alert('请登录','jump','admin/login');
	    }
	    if(!$level = getLevel($manage_id)){
        	alert('当前用户无权限角色','close');
        }

	    if($level['news_edit'] == 0){
	    	alert('当前权限不够，请勿进行当前操作','close');
	       // $this->error('当前权限不够，请勿进行当前操作');
	    }

		$data = input('post.');
		//var_dump($data);
		$data['publish_time'] = date('Y-m-d H:i:s', time());
		//保存草稿 修改内容 status 0
		if(isset($data['save']) && $data['save'] == 'save'){
			$data['status'] = 0;
			$news = Db::table('jyurec_sec_news')->where('id',$_GET['id'])->strict(false)->data($data)->update();
			if($news){
				$this->success('保存成功');
			}
		}
		//保存草稿并提交审核 修改内容 status 2
		if(isset($data['save_audit']) && $data['save_audit'] == 'save_audit'){
			$data['status'] = 2;
			$news = Db::table('jyurec_sec_news')->where('id',$_GET['id'])->strict(false)->data($data)->update();
			if($news){
				if($level['news_audit'] == 0){
			        alert('等待审核！','jump','news_list');
			    }else{
			    	alert('提交审核！','jump','news_audit');
			    }
				
			}
		}	
	}

	//修改新闻状态  0草稿 1已发布 2审核 3审核不通过
  	public function audit_news(){
  		session_start();
	    $manage_id = is_manage();

	    if(!$manage_id){
	      alert('请登录','jump','admin/login');
	    }
	    if(!$level = getLevel($manage_id)){
        	alert('当前用户无权限角色','close');
        }

	    if($level['news_audit'] == 0){
	    	alert('当前权限不够，请勿进行当前操作','close');
	        //$this->error('当前权限不够，请勿进行当前操作');
	    }

	    $id=input('id');
	    $k = input('k');
	    if(!isset($id) || !is_numeric($id) ||!isset($k) || !is_numeric($k)){
	      return false;
	    }
	    if($k == 1){//审核通过
	      //修改新闻状态为1      
	      $audit_news = Db::table('jyurec_sec_news')->where('id',$id)->update(['status'=>1]);
	      if($audit_news){
	        return true;
	      }else{
	        return false;
	      }    
    	}

	    if($k == 3){//审核不通过
	      //修改新闻状态为0
	    
	        $audit_news = Db::table('jyurec_sec_news')->where('id',$id)->update(['status'=>3]);
	      if($audit_news){
	       return true;
	      }else{
	        return false;
	      }
	    }
	}

	/**
	 * 资讯审核
	 * status 审核状态 0未通过 1通过
	 * type   资讯类型 0视频 1新闻
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

	      if($level['news_audit'] == 0){
        	return false;
	        //$this->error('当前权限不够，请勿进行当前操作');
	      }

	      $id=input('id');
	      $status = input('status');
	      $type = input('type');
	      if(!isset($id) || !is_numeric($id) ||!isset($status) || !is_numeric($status) ||!isset($type) || !is_numeric($type)){
	        return false;
	      }

	      if($status == 1){//审核通过
	      	if($type == 0){
	      		$VideoModel = new VideoModel();
	        	return $VideoModel->audit_handle($id,$manage_id,'1'); 
	      	}
	      	if($type == 1){
	      		$NewsModel = new NewsModel();
	        	return $NewsModel->audit_handle($id,$manage_id,'1'); 
	      	}
	      }
	      if($status == 0){//审核不通过
	        $cause = input('cause');
	        if($type == 0){
	        	$VideoModel = new VideoModel();
	        	return  $VideoModel->audit_handle($id,$manage_id,'0',$cause); 
	        }
	        if($type == 1){
	        	$NewsModel = new NewsModel();
	        	return  $NewsModel->audit_handle($id,$manage_id,'0',$cause); 
	        }
	      }
	      return false;
	}
	
}

?>
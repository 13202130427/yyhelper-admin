<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
class Video extends Model{

	use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $updateTime = 'update_time';


	public function getVideoList(){
		$result = $this->with(['user'=>function($query){$query->field('id,name');}])
		->select();
		$list= [];
		foreach ($result as $key => $value) {
			$list[] = $value->toArray();
		}
		return $list;
		return $this->select();
	}

	public function getVideoAuditList(){
		$result = $this->with(['user'=>function($query){$query->field('id,name');}])->where('status',2)
		->select();
		$list= [];
		foreach ($result as $key => $value) {
			$list[] = $value->toArray();
		}
		return $list;
		return $this->select();
	}

	public function getVideoCount(){
		return $this->count();
	}
	public function getVideoAuditCount(){
		return $this->where('status',2)->count();
	}

	public  function getVideoInfo($id){
		$result = $this->with(['videoinfo'=>function($query){$query->field('id,video_id,name,url,page');}])->where('id',$id)
		->find();
		return $result->toArray();
	}

	public function videoDel($id){
		$video = $this->get($id);
		return $video->delete();
	}
	/**
	 * 视频审核
	 * @param  [int] $id     [视频ID]
	 * @param  [int] $admin_id     [管理员ID]
	 * @param  [int] $status [审核状态]  0未通过 1通过
	 * @param  [string] $cause  [原因]
	 * @return [bool]         [description]
	 */
	public function audit_handle($id,$admin_id,$status,$cause=null){
		$video = $this->get($id);
		if($status == 0){
			return $video->save(['status'=>0,'cause'=>$cause,'admin_id'=>$admin_id]);
		}
		if($status == 1){
			return $video->save(['status'=>1,'admin_id'=>$admin_id]);
		}
		return false;
	}

	public function getVideoStatistics(){
		$list= [];
		$list['all_count'] = $this->count();
		$list['today'] = $this->whereTime('time','today')->count();
		$list['yesterday'] = $this->whereTime('time','yesterday')->count();
		$list['week'] = $this->whereTime('time','week')->count();
		$list['month'] = $this->whereTime('time','month')->count();

		return $list;
	}

	public function user(){
		return $this->hasOne('user','id','userId');
	}

	public function videoinfo(){
		return $this->hasMany('videoinfo','video_id','id');
	}


}
?>
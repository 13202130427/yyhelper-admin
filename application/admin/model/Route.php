<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use traits\model\SoftDelete;
class Route extends Model{

	use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $updateTime = 'update_time';


	public function getRouteList(){
		$result = $this->with(['industrytwo'=>function($query){$query->field('id,name');}
			,'jobthree'=>function($query){$query->field('id,name');}
			,'user'=>function($query){$query->field('id,name');}
			])
		->select();
		$list= [];
		foreach ($result as $key => $value) {
			$list[] = $value->toArray();
		}
		return $list;
	}

	public function getRouteCount(){
		return $this->count();
	}

	public function getRouteAuditList(){
		$result = $this->with(['industrytwo'=>function($query){$query->field('id,name');}
			,'jobthree'=>function($query){$query->field('id,name');}
			,'user'=>function($query){$query->field('id,name');}
			])
		->where('status',2)->select();
		$list= [];
		foreach ($result as $key => $value) {
			$list[] = $value->toArray();
		}
		return $list;
	}

	public function getRouteAuditCount(){
		return $this->where('status',2)->count();
	}


	public function getRouteInfo($id){
		$result =  $this->get($id)->study;
		$list= [];
		foreach ($result as $key => $value) {
			$list[$key] = $value->toArray();
			$list[$key]['video'] = Db::name('video')->where('id',$value['video_id'])->find();
			$list[$key]['videoinfo'] = Db::name('videoinfo')->where('video_id',$value['video_id'])->order("page","asc")->select();
		}
		return $list;
	}
	/**
	 * 路线审核
	 * @param  [int] $id     [路线ID]
	 * @param  [int] $admin_id     [管理员ID]
	 * @param  [int] $status [审核状态]  0未通过 1通过
	 * @param  [string] $cause  [原因]
	 * @return [bool]         [description]
	 */
	public function audit_handle($id,$admin_id,$status,$cause=null){
		$route = $this->get($id);
		if($status == 0){
			return $route->save(['status'=>0,'cause'=>$cause,'admin_id'=>$admin_id]);
		}
		if($status == 1){
			return $route->save(['status'=>1,'admin_id'=>$admin_id]);
		}
		return false;
	}

	public function routeDel($id){
		$route = $this->get($id);
		return $route->delete();
	}

	public function getRouteStatistics(){
		$list= [];
		$list['all_count'] = $this->count();
		$list['today'] = $this->whereTime('time','today')->count();
		$list['yesterday'] = $this->whereTime('time','yesterday')->count();
		$list['week'] = $this->whereTime('time','week')->count();
		$list['month'] = $this->whereTime('time','month')->count();

		return $list;
	}
	public function industrytwo(){
		return $this->hasOne('industrytwo','id','industry_id');
	}

	public function jobthree(){
		return $this->hasOne('jobthree','id','job_id');
	}


	public function user(){
		return $this->hasOne('user','id','userId');
	}

	public function study(){
		return $this->hasMany('study','route_id','id');
	}	
}
?>
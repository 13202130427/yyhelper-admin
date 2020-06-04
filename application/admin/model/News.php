<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
class News extends Model{

	use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $updateTime = 'update_time';


	public function getNewsList(){
		$result = $this->with(['university'=>function($query){$query->field('id,name');},'colleges'=>function($query){$query->field('id,name');}])
		->select();
		$list= [];
		foreach ($result as $key => $value) {
			$list[] = $value->toArray();
		}
		return $list;
		return $this->select();
	}

	public function getNewsCount(){
		return $this->count();
	}

	public function getNewsAuditList(){
		$result = $this->with(['university'=>function($query){$query->field('id,name');},'colleges'=>function($query){$query->field('id,name');}])
		->where('status',2)->select();
		$list= [];
		foreach ($result as $key => $value) {
			$list[] = $value->toArray();
		}
		return $list;
		return $this->select();
	}

	public function getNewsAuditCount(){
		return $this->where('status',2)->count();
	}
	/**
	 * 新闻删除
	 * @param  [int] $id     [新闻ID]
	 * @return [bool]         [description]
	 */
	public function newsDel($id){
		$news = $this->get($id);
		return $news->delete();
	}

	/**
	 * 新闻审核
	 * @param  [int] $id     [新闻ID]
	 * @param  [int] $admin_id     [管理员ID]
	 * @param  [int] $status [审核状态]  0未通过 1通过
	 * @param  [string] $cause  [原因]
	 * @return [bool]         [description]
	 */
	public function audit_handle($id,$admin_id,$status,$cause=null){
		$news = $this->get($id);
		if($status == 0){
			return $news->save(['status'=>0,'cause'=>$cause,'admin_id'=>$admin_id]);
		}
		if($status == 1){
			return $news->save(['status'=>1,'admin_id'=>$admin_id]);
		}
		return false;
	}

	public function getNewsStatistics(){
		$list= [];
		$list['all_count'] = $this->count();
		$list['today'] = $this->whereTime('time','today')->count();
		$list['yesterday'] = $this->whereTime('time','yesterday')->count();
		$list['week'] = $this->whereTime('time','week')->count();
		$list['month'] = $this->whereTime('time','month')->count();

		return $list;
	}

	public function university(){
		return $this->hasOne('university','id','university_id');
	}
	
	public function colleges(){
		return $this->hasOne('colleges','id','colleges_id');
	}
}
?>
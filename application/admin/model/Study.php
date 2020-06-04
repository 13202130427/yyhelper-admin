<?php
namespace app\admin\model;
use think\Model;
class Study extends Model{

	public function video(){
		return $this->hasOne('video','id','video_id');
	}
}
?>
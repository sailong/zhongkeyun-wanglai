<?php
class FollowController extends FController
{
	
	public function loadModel($new=false)
	{
		return $new ? new Follow() : Follow::model();
	}
	/**
	 * 关注 取消关注
	 */
    public function actionFollow()
    {   
    	$this->checkParam(array('mid','follow_mid','sign','tag'));
    	extract($this->params);
    	if($sign!=Helper::createSign($mid.'-'.$follow_mid)) $this->returnData(0,'非法提交');
    	if(!$this->getOpenid() || !$mid) $this->returnData(-1,'您还没有登录呢');
    	if($mid==$follow_mid) $this->returnData(0,'您不能关注您自己');
    	$is_follow = $this->loadModel()->checkFollow($mid,$follow_mid);
    	if($tag==1)
    	{
    		if($is_follow>0) $this->returnData(0,'您已经关注过此用户！');
    		
    		$time = time();
    		if($is_follow==-1)
    		{
    			$model = $this->loadModel(true);
    			$model->mid = $mid;
    			$model->follow_mid = $follow_mid;
    			$model->follow_at  = $time;
    			$model->is_new = Follow::NEW_YES;
    			$model->save();
    		}elseif ($is_follow==-2)
    		{
    			$this->loadModel()->updateAll(array('is_deleted'=>0,'follow_at'=>$time,'is_new'=>Follow::NEW_YES),'mid=:a AND follow_mid=:b',array(':a'=>$mid,':b'=>$follow_mid));
    		}
    		//更新通知
    		Stat::model()->updateAll(array('last_follow_at'=>$time,'last_update_at'=>$time),'member_id='.$follow_mid);
    	}else 
    	{
    		$this->loadModel()->updateAll(array('is_deleted'=>1,'is_new'=>Follow::NEW_NO),'mid=:a AND follow_mid=:b',array(':a'=>$mid,':b'=>$follow_mid));
    	}
    	$this->returnData(1);
    }
}
?>
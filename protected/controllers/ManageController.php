<?php
/**
 * 名片夹主页
 * @author JZLJS00
 *
 */
class ManageController extends FrontController
{

	/**
	 * @see CController::filters()
	 */
	public function filters()
	{
		return array(
			'accessControl - view',
			'ajaxOnly + countShare'
		);
	}
	
	public function accessRules()
	{
		return array(
			array('deny',
				'actions' => array('followMe','index','interFollow','myFollow','myFriend','myPartner','myViewer','search'),
				'users'   => array('?'),
				'deniedCallback' => array($this,'deny')
			)
		);
	}
	
	
	public function deny()
	{
		$refer = Yii::app()->request->getRequestUri();
		Util::addCookie('refer', $refer);
		$this->goAuth();
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see CController::behaviors()
	 */
	public function behaviors()
	{
		return array(
			'manage' => 'application.components.behaviors.ManageBehavior'
		);
	}
	
	/**
	 * 管理我的名片首页
	 */
    public function actionIndex()
    {   
    	$model = $this->_member;
		$this->renderPartial('index',compact('model'));   
    }
    
    /**
     * 关注我的人,但不是我关注的(我关注，关注我,相互关注共用模板follow)
     */
    public function actionFollowMe()
    {
    	if(Follow::model()->calculateNewFollow())
    	{
    		$this->onViewFollow(new CEvent($this));
    	}
    	$id = Yii::app()->user->id;
    	$sql = "SELECT follow.mid FROM follow WHERE follow.follow_mid={$id} AND is_deleted=".Follow::FOLLOW_IN." AND follow.mid NOT IN(SELECT follow_mid FROM follow WHERE `mid`={$id} AND is_deleted=".Follow::FOLLOW_IN.")";
    	$follows = Yii::app()->db->createCommand($sql)->queryColumn();
    	$criteria = new CDbCriteria;
    	$criteria->select = 'id,name,avatar,position,company,mobile,address';
    	$criteria->addInCondition('id',$follows);
    	$keyword = trim(Yii::app()->request->getParam('keyword',''));
    	if(!empty($keyword))
    	{
    		$keyword = htmlspecialchars($keyword, ENT_QUOTES);
    		$criteria2 = new CDbCriteria();
    		$criteria2->addSearchCondition('name', $keyword,true,'OR')->addSearchCondition('mobile', $keyword,true,'OR')
    		->addSearchCondition('position', $keyword,true,'OR')->addSearchCondition('company', $keyword,true,'OR');
    		$criteria->mergeWith($criteria2);
    	}
    	$data = Member::model()->initial()->findAll($criteria);
    	// 关注我的信息
    	$followMeInfo = array_fill_keys($follows, true);   // 这些人是都是关注我的
    	// 我关注这些人的信息
    	$myFollowInfo = array_fill_keys($follows, false);  // 我没有关注这些人
    	if(Yii::app()->request->getIsAjaxRequest())
    	{
    		$html = $this->renderPartial('_list', compact('data', 'followMeInfo'), true);
    		$this->returnData(1, $html);
    	}else{
	    	$title = '关注我的';
	    	$this->render('follow',compact('data','title','followMeInfo','myFollowInfo'));
    		
    	}
    }

    /**
     * 我关注的人,但不关注我的(我关注，关注我,相互关注共用模板follow)
     */
    public function actionMyFollow()
    {
    	$id = Yii::app()->user->id;
    	$sql = "SELECT follow.follow_mid FROM follow WHERE follow.mid={$id} AND is_deleted=".Follow::FOLLOW_IN." AND follow.follow_mid NOT IN(SELECT f.mid FROM follow AS f WHERE f.follow_mid={$id} AND is_deleted=".Follow::FOLLOW_IN.")";
    	$follows = Yii::app()->db->createCommand($sql)->queryColumn();
    	$criteria = new CDbCriteria;
    	$criteria->select = 'id,name,avatar,position,company,mobile,address';
    	$criteria->addInCondition('id',$follows);
    	$keyword = trim(Yii::app()->request->getParam('keyword',''));
    	if(!empty($keyword))
    	{
    		$keyword = htmlspecialchars($keyword, ENT_QUOTES);
    		$criteria2 = new CDbCriteria();
    		$criteria2->addSearchCondition('name', $keyword,true,'OR')->addSearchCondition('mobile', $keyword,true,'OR')
    		->addSearchCondition('position', $keyword,true,'OR')->addSearchCondition('company', $keyword,true,'OR');
    		$criteria->mergeWith($criteria2);
    	}
    	$data = Member::model()->initial()->findAll($criteria);
    	// 关注我的信息
    	$followMeInfo = array_fill_keys($follows, false);   // 这些人是都是关注我的
    	// 我关注这些人的信息
    	$myFollowInfo = array_fill_keys($follows, true);  // 我没有关注这些人
    	if(Yii::app()->request->getIsAjaxRequest())
    	{
    		$html = $this->renderPartial('_list', compact('data', 'followMeInfo'), true);
    		$this->returnData(1, $html);
    	}else{
    		$title = '我关注的';
    		$this->render('follow',compact('data','title','followMeInfo','myFollowInfo'));
    	}
    }
    
    /**
     * 我关注的,关注我的,相互关注的(我关注，关注我,相互关注共用模板follow)
     */
    public function actionInterFollow()
    {
    	if(Follow::model()->calculateNewFollow())
    	{
    		$this->onViewFollow(new CEvent());
    	}
    	$id = Yii::app()->user->id;
    	$sql = "SELECT follow.follow_mid FROM follow INNER JOIN follow AS f ON follow.follow_mid=f.mid AND follow.mid={$id} AND follow.is_deleted=".Follow::FOLLOW_IN." AND f.follow_mid={$id} AND f.is_deleted=".Follow::FOLLOW_IN;
    	$interFollow = Yii::app()->db->createCommand($sql)->queryColumn();
    	$criteria = new CDbCriteria;
    	$criteria->select = 'id,name,avatar,position,company,mobile,address';
    	$criteria->addInCondition('id',$interFollow);
    	$keyword = trim(Yii::app()->request->getParam('keyword',''));
    	if(!empty($keyword))
    	{
    		$keyword = htmlspecialchars($keyword, ENT_QUOTES);
    		$criteria2 = new CDbCriteria();
    		$criteria2->addSearchCondition('name', $keyword,true,'OR')->addSearchCondition('mobile', $keyword,true,'OR')
    		->addSearchCondition('position', $keyword,true,'OR')->addSearchCondition('company', $keyword,true,'OR');
    		$criteria->mergeWith($criteria2);
    	}
    	$data = Member::model()->initial()->findAll($criteria);
    	// 关注我的信息
    	$followMeInfo = array_fill_keys($interFollow, true);   // 这些人是都是关注我的
    	// 我关注这些人的信息
    	$myFollowInfo = array_fill_keys($interFollow, true);  // 我没有关注这些人
    	if(Yii::app()->request->getIsAjaxRequest())
    	{
    		$html = $this->renderPartial('_list', compact('data', 'followMeInfo'), true);
    		$this->returnData(1, $html);
    	}else{
    		$title = '我的好友';
    		$this->render('follow',compact('data','title','followMeInfo','myFollowInfo'));
    	}
    }
    
    /**
     * 
     * @param unknown_type $event
     */
    public function onViewFollow($event)
    {
    	$this->raiseEvent('onViewFollow', $event);
    }
    
    /**
     * 看过我名片的人
     * 与我的小伙伴共用模板list
     */
    public function actionMyViewer()
    {
    	$id = Yii::app()->user->id;
    	$sql = "SELECT member.id,`name`,`position`,avatar,company,mobile,address,follow.is_deleted FROM member JOIN view_log AS vl ON vl.member_id = member.id AND vl.viewd_member_id = {$id} LEFT JOIN follow ON member.id = follow.follow_mid AND follow.mid = {$id} ORDER BY vl.last_viewd_at DESC";
    	$result = Yii::app()->db->createCommand($sql)->queryAll();
    	$data = array();
    	$myFollowInfo = array();
    	$followMeInfo = array();
    	$keyword = trim(Yii::app()->request->getParam('keyword',''));
    	if(!empty($result))
    	{
    		$memberIds = array();
    		foreach ($result as $value)
    		{
    			$myFollowInfo[$value['id']] = (is_null($value['is_deleted']) || $value['is_deleted'] == Follow::FOLLOW_OUT) ? false : true;
    			unset($value['follow']);
    			array_push($memberIds, $value['id']);
    			if(!empty($keyword))
    			{
    				$keyword = htmlspecialchars($keyword,ENT_QUOTES);
    				if(strpos($value['name'], $keyword) !== false)
    				{
    					$data[] = (object) $value;
    				}elseif(strpos($value['mobile'], $keyword) !== false)
    				{
    					$data[] = (object) $value;
    				}else
    				{
    					continue;
    				}
    			}else{
	    			$data[] = (object) $value;
    			}
    		}
    		$followMeInfo = Follow::model()->checkMultiFollow($id, $memberIds);
    	}
    	if(Yii::app()->request->getIsAjaxRequest())
    	{
    		$html = $this->renderPartial('_list', compact('data', 'followMeInfo'), true);
    		$this->returnData(1, $html);
    	}else{
    		$title = '我的访客';
    		$this->render('follow',compact('data','title','followMeInfo','myFollowInfo'));
    	}
    }
    
    
    /**
     * 我的小伙伴
     * 与看过我名片的人共用模板list
     */
//     public function actionMyPartner()
//     {
//     	if(Partner::model()->hasNewPartner())
//     	{
//     		$this->onViewPartner(new CEvent($this));
//     	}
//     	$id = Yii::app()->user->id;
//     	$total = Partner::model()->calculateMyPartner($id);
//     	$pagination = new CPagination($total);
//     	$data = array();
//     	if($total>0)
//     	{
//     		$sql = "SELECT member.id,`name`,`position`,avatar,company,follow.is_deleted FROM member JOIN partner ON partner.new_uid = member.id AND partner.from_uid = {$id} LEFT JOIN follow ON member.id = follow.follow_mid AND follow.mid = {$id} ORDER BY initial DESC LIMIT ".$pagination->getOffset().",".$pagination->getPageSize();
//     		$data = Yii::app()->db->createCommand($sql)->queryAll();
//     	}
//     	$title = '我的小伙伴';
//     	$this->render('list',compact('data','pagination','title'));
//     }
    
    public function onViewPartner($event)
    {
    	$this->raiseEvent('onViewPartner', $event);
    }
    
    /**
     * 好友微名片排行榜(之前有我的小伙伴,现已没有)
     * 我关注的/关注我的
     */
    public function actionMyFriend() 
    {
    	$id = Yii::app()->user->id;
    	$sql = "SELECT * FROM follow WHERE (`mid`={$id} OR follow_mid={$id}) AND is_deleted=".Follow::FOLLOW_IN;
    	$result = Yii::app()->db->createCommand($sql)->queryAll();
    	$data = array();
    	if(!empty($result))
    	{
    		$ids = array();
    		foreach ($result as $value)
    		{
    			$ids[] = $value['mid'] == $id ? $value['follow_mid'] : $value['mid'];
    		}
	    	$criteria=new CDbCriteria();
	    	$criteria->addInCondition('t.id', $ids);
	    	$criteria->scopes = 'hot';
	    	$criteria->with = 'follow';
    		$data =  Member::model()->findAll($criteria);
	    	$keyword = trim(Yii::app()->request->getParam('keyword',''));
	    	$i = 0;
	    	foreach ($data as $key => &$value)
	    	{
	    		$i++;
	    		if(!empty($keyword))
	    		{
	    			if(strpos($value->name, $keyword) !== false)
	    			{
	    				$value->score = $i;
	    				continue;
	    			}elseif(strpos($value->mobile, $keyword) !== false)
	    			{
	    				$value->score = $i;
	    				continue;
	    			}else
	    			{
	    				unset($data[$key]);
	    				continue;
	    			}
	    		}else
	    		{
	    			$value->score = $i;
	    		}
	    	}
    		$myFollowInfo = Follow::model()->checkMultiFollow2($id, $ids);
    	}
    	if(Yii::app()->request->getIsAjaxRequest())
    	{
    		$html = $this->renderPartial('_rank', compact('data'), true);
    		$this->returnData(1, $html);
    	}else{
    		$this->render('friend',compact('data','myFollowInfo'));
    	}
    	
    	
    }
    
    /**
     * 搜索,2处，1个是名片夹主页(6项合集的搜索),2是名片夹详细页(3项搜索)
     */
    public function actionSearch()
    {
    	$keyword = trim(Yii::app()->request->getParam('keyword'));
    	if(empty($keyword))
    	{
    		$this->showMessage(0, '搜索关键词为空');
    	}
    	$refer = Yii::app()->request->getUrlReferrer();
    	$from = stripos($refer, 'myFollow')>0 || stripos($refer, 'followMe')>0 || stripos($refer, 'interFollow')>0 ? 'follow' : 'index';
    	$id = Yii::app()->user->id;
    	$sql = "SELECT `mid`,follow_mid FROM follow WHERE `mid`={$id} OR follow_mid={$id}";
    	$alls = array();
    	$part1 = Yii::app()->db->createCommand($sql)->queryAll();
    	foreach ($part1 as $value)
    	{
    		if($value['mid'] != $id)
    		{
    			array_push($alls, $value['mid']);
    		}
    		if($value['follow_mid'] != $id)
    		{
    			array_push($alls, $value['follow_mid']);
    		}
    	}
    	if($from == 'index')
    	{
	    	$sql = "(SELECT member_id AS `mid` FROM view_log WHERE viewd_member_id={$id}) UNION (SELECT new_uid AS `mid` FROM partner WHERE from_uid={$id})";
	    	$part2 = Yii::app()->db->createCommand($sql)->queryColumn();
	    	if(!empty($part2))
	    	{
	    		$alls = array_merge($alls, $part2);
	    	}
    	}
    	$dataProvider = array();
    	if(!empty($alls))
    	{
    		$alls = array_unique($alls);
    		$criteria = new CDbCriteria;
    		$criteria->addInCondition('t.id',$alls);
    		$criteria->select = 't.id,name,avatar,position,company';
    		$criteria->with = 'follow';
    		$criteria->scopes = 'initial';
    		$criteria2 = new CDbCriteria();
    		$criteria2->addSearchCondition('name', $keyword,true,'OR')->addSearchCondition('mobile', $keyword,true,'OR')
    				  ->addSearchCondition('position', $keyword,true,'OR')->addSearchCondition('company', $keyword,true,'OR');
    		$criteria->mergeWith($criteria2);
    		$dataProvider = new CActiveDataProvider('member',array(
    				'criteria' => $criteria,
    				'pagination' => array(
    					'pageVar' => 'page'
    			)
    		));
    	}
    	$this->render('search',compact('dataProvider','keyword','from'));
    }
    
}
?>
<?php
/**
 * 菜单
 * @author JZLJS00
 *
 */
class Menu extends CWidget
{
	
	/**
	 * 菜单
	 * @var unknown_type
	 * 
	 */
	public $menus = array(
		array(
			'title' => '首页',
			'controllerId' => 'index',
			'actionId' => 'index'	
		),
		array(
			'title' => '微名片',
			'children' => array(
				array(
					'title' 		=> '我的名片',
					'controllerId'  => 'member',
					'actionId' 		=> 	'index',
				),
				array(
					'title' 		=> '名片二维码',
					'controllerId'  => 'member',
					'actionId'      => 'getQRcode'	
				),
				array(
					'title'			=> '修改名片',
					'controllerId'	=> 'member',
					'actionId'		=> 'update'
				)
			)	
		),
		array(
			'title' => '微人脉',
			'children' => array(
				array(
					'title'			=> '名片夹',
					'controllerId'	=> 'manage',
					'actionId'      => 'index',
				),
				array(
					'title'			=> '排行榜',
					'controllerId'	=> 'manage',
					'actionId'		=> 'myFriend'
				),
				array(
					'title'			=> '谁看过我',
					'controllerId'	=> 'manage',
					'actionId'		=> 'myViewer'
				),
				array(
					'title'			=> '微群通讯录',
					'controllerId'	=> 'contacts',
					'actionId'		=> 'myCreated'
				), 
			)		
		),
		array(
			'title' => '微活动',
			'children' => array(
				array(
					'title'			=> '发起活动',
					'controllerId'	=> 'activity',
					'actionId'		=> 'create'
				),
				array(
					'title'			=> '我发起的',
					'controllerId'	=> 'activity',
					'actionId'		=> 'myCreated'
				),
				array(
					'title'			=> '我参与的',
					'controllerId'	=> 'activity',
					'actionId'		=> 'myJoined'
				),
				array(
					'title'			=> '送贺卡',
					'controllerId'	=> 'bless',
					'actionId'		=> 'index'
				),
			)		
		)
	);
	
	/**
	 * 一级分类的类名
	 * @var unknown_type
	 */
	public $levelOneClass = 'tit';
	
	/**
	 * 当前操作的类名
	 * @var unknown_type
	 */
	public $currentClass = 'cur';
	
	
	public function run()
	{
		$controller = $this->getController();
		$currentControllerId = $controller->getId();
		$currentActionId = $controller->getAction()->getId();
		$html = '<menu id="menu">';
		foreach ($this->menus as $menu)
		{
			if(!isset($menu['children']))
			{
				$url = $controller->createUrl($menu['controllerId'] . '/' . $menu['actionId']);
				if($currentControllerId == $menu['controllerId'] && $currentActionId == $menu['actionId'])
					$html .= '<li class="'.$this->currentClass.'"><a href="'.$url.'">'.$menu['title'].'</a></li>';
				else 
					$html .= '<li><a href="'.$url.'">'.$menu['title'].'</a></li>';
			}else 
			{
				$html .= '<li class="'.$this->levelOneClass.'">'.$menu['title'].'</li>';
			}
			
			if(!empty($menu['children']))
			{
				foreach ($menu['children'] as $child)
				{
					$url = $controller->createUrl($child['controllerId'] . '/' . $child['actionId']);
					if($child['controllerId'] == $currentControllerId && $child['actionId'] == $currentActionId)
						$html .= '<li class="'.$this->currentClass.'">';
					elseif($child['controllerId'] == 'member' && $child['actionId'] == 'index' && $currentControllerId == 'member' && $currentActionId == 'view' && Yii::app()->request->getParam('id') == Yii::app()->user->id)
						$html .= '<li class="'.$this->currentClass.'">';
					else
						$html .= '<li>';
					$html .= '<a href="'.$url.'">'.$child['title'].'</a></li>';
				}
			}
		}
		
		$html .= '</menu>';
		echo $html;
	}
	
}
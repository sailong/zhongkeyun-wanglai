<?php

/**
 * 临时一个活动需要
 * @author JZLJS00
 *
 */
class ResearchController extends FrontController
{
	
	public $param = array(
			
		'type' => array(
				1=>'IT信息服务',2=>'节能环保',3=>'健康医疗',4=>'新能源',5=>'新材料',6=>'制造业',
				7=>'餐饮服务',8=>'金融服务',9=>'物流服务',10=>'其它'		
		),
		'stage' => array(
				1=>'创业期',2=>'迅速扩张期',3=>'稳定发展期',4=>'多元发展期',5=>'业务停滞期',6=>'出现下滑趋势期',7=>'其它'	
		),
		'cost' => array(
				1=>'产品成本',2=>'服务成本',3=>'运营成本',4=>'物料成本',5=>'人工成功',6=>'机器设备成本'	
		),
		'information' => array(
				1=>'没有任何系统',2=>'财务管理系统',3=>'办公自动系统(OA)',4=>'管理信息系统',5=>'计算机辅助设计(CAD)计算机辅助制造(CAM)物料需求计划(MRP)',
				6=>'制造资源计划(MRPII)企业资源计划(ERP)',7=>'决策支持系统(DSS)',8=>'客户关系管理系统(CRM)计算机集成制造系统(CIMS)'	,9=>'供应链管理(SCM)经理信息系统',
				10=>'人力资源信息系统(HR)',11=>'采购系统',12=>'订单管理系统',13=>'营销系统',14=>'库存管理系统',15=>'业务管理系统',16=>'经营分析系统',
				17=>'电子商务系统',18=>'其它'
		),
		'web' => array(
				1=>'是',2=>'否,计划建',3=>'否,暂时没有计划'	
		),
		'function' => array(
				1=>'宣传',2=>'产品介绍',3=>'开展电子商务交易',4=>'其它'		
		),
		'sale_channel' => array(
				1=>'自营店面零售',2=>'商超零售',3=>'线下批发代理',4=>'电商零售',5=>'电商批发'
		),
		'promotion_channel' => array(
				1=>'自建网站',2=>'微博',3=>'微信',4=>'新浪搜狐等线上媒体渠道',5=>'淘宝京东等电商渠道',6=>'百度等搜索引擎渠道'	
		),
		'impact' => array(
				1=>'生产流程',2=>'工作流程',3=>'管理模式',4=>'组织结构',5=>'市场营销',6=>'其它'
		),
		'advantage' => array(
				1=>'产品品质',2=>'服务品质',3=>'劳动效率',4=>'技术',5=>'品牌',6=>'商业模式',7=>'组织模式',8=>'管理模式',9=>'市场营销',
				10=>'成本优势(廉价提供产品服务)',11=>'增值优势(更吸引人的增值产品服务)',12=>'聚焦优势(准确锁定顾客群需求)',13=>'速度优势(快速满足顾客需求)',
				14=>'机动优势(适应竞争变化)'
		),
		'disadvantage' => array(
				1=>'利润增长',2=>'市场份额',3=>'战略管理',4=>'品牌经营',5=>'网络营销'	,6=>'组织结构',
				7=>'管理模式',8=>'管理团队',9=>'其它'
		),
	);
	
	
	public function actionIndex()
	{
		$activity_id = intval(Util::decode(Yii::app()->request->getParam('id')));
		$activity = Activity::model()->findByPk($activity_id);
		$model = Research::model()->findAllByAttributes(array('mid'=>Yii::app()->user->id));
		if(!empty($model))
		{
			Yii::app()->user->setFlash('apply','您已报名过,不需要再填写问卷');
			$url = $this->createUrl('/activity/detail',array('id'=>$activity->id));
			$this->redirect($url);
			exit();
		}
		
		if(!empty($activity) && $activity_id == Activity::$special_id)
		{
			if(isset($_POST['Research']))
			{
				$param = $_POST['Research'];
				
				
				$checkbox = array('type','cost','information','sale_channel','promotion_channel','impact','disadvantage');
				$radio = array('stage','web','function','advantage');
				
				foreach ($param as $key => $value)
				{
					if(in_array($key, $checkbox))
					{
						$value = explode(',', $value);
						array_pop($value);
						$tmp = array();
						foreach ($value as $v)
						{
							if(isset($this->param[$key][$v]))
								$tmp[] = $this->param[$key][$v];
						}
						$else = trim($param[$key.'_else']);
						if(!empty($else))
							$tmp[] = $else;
						
						$param[$key] = join(',', $tmp);
					}elseif(in_array($key, $radio))
					{
						$param[$key] = $this->param[$key][$value];
					}else{
						$param[$key] = trim($value);
					}
				}
				$param['mid'] = Yii::app()->user->id;
				$param['create_time'] = time();
				
				
				$model = new Research();
				$model->attributes = $param;
				
				if($model->save())
				{
					// 发送问卷结果
					$this->sendMail($model, $activity);
					
					// 报名参加活动
					$result = Activity::model()->apply($activity_id,Yii::app()->user->id);
					$url = $this->createUrl('/activity/detail',array('id'=>$activity->id));
					Yii::app()->user->setFlash('apply','报名成功');
					$this->redirect($url);
				}else{
					$error = array_values($model->getErrors());
					Yii::app()->user->setFlash('apply', $error[0][0]);
				}
			}
			$this->render('create');
		}
	}
	
	private function sendMail($research, $activity)
	{
		$owner = Member::model()->findByPk($activity->create_mid);
		if($owner !== null && !empty($owner->email))
		{
			$html = $this->renderPartial('/research/sendmail',array('model'=>$research,'member'=>$this->_member),true);
			$emails = Activity::$emails;
			array_push($emails, $owner->email);
			Util::sendMail($emails, $activity->title . '报名成员调查问卷', $html);
		}
	}
	
	
}
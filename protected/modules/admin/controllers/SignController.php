<?php

/**
 * 后台签名
 * @author JZLJS00
 *
 */
class SignController extends AdminController
{
	
	public $nav = '签名活动';
	/**
	 * 
	 */
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('SignActivity',array(
				'criteria' => array(
					'order'  => 'id desc'
				),
				'pagination' => array(
					'pageSize' => 10,
				)
		));
		
		$this->render('index',array('dataProvider'=>$dataProvider));
	}
	
	
	public function actionCreate()
	{
		$model = new SignActivity();
		$publish_uids = $_POST['publish_uids'];
		
		if(isset($_POST['SignActivity']) && !empty($publish_uids))
		{
		    if(!empty($publish_uids))
		    {
		        preg_match('/id:(\d+)/', $publish_uids,$match);
		        if(!empty($match[1]))
		        {
		            $create_mid = (int)$match[1];
		        }
		    }
		    $_POST['SignActivity']['create_mid'] = $create_mid;
		    $_POST['SignActivity']['publish'] = 1;
			$model->attributes = $_POST['SignActivity'];
			$file = CUploadedFile::getInstance($model,'img');   //获得一个CUploadedFile的实例
			if(is_object($file) && get_class($file) === 'CUploadedFile')
			{
				$model->img = 'static/images/sign/'.time().rand(0,100).'.'.$file->extensionName;
			}
			$model->create_time = time();
			$model->publish_time = time();
			if($model->save())
			{
				if(is_object($file) && get_class($file) === 'CUploadedFile'){  
			 		$file->saveAs($model->img);    // 上传图片  
                }
                $this->redirect($this->createUrl('index'));  
			}else{
				print_r($model->getErrors());
			}
		}
		$this->render('create',array('model'=>$model));
		
	}
	
	public function actionUpdate()
	{
		$id = intval(Yii::app()->request->getParam('id'));
		$model = SignActivity::model()->findByPk($id);
        $sql = "SELECT * FROM member WHERE id='".$model->create_mid."'";
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		
		if(!empty($result))
		{
	        $create_mids = $result['name'] . '(职位：'.$result['position'].',手机：'.$result['mobile'].',公司：'.$result['company'].',id:'.$result['id'].')';			    
		    unset($result);
		}
		$publish_uids = $_POST['publish_uids'];
		
		if(isset($_POST['SignActivity']) && !empty($publish_uids))
		{
		    if(!empty($publish_uids))
		    {
		        preg_match('/id:(\d+)/', $publish_uids,$match);
		        if(!empty($match[1]))
		        {
		            $create_mid = (int)$match[1];
		        }
		    }
		    $_POST['SignActivity']['create_mid'] = $create_mid;
		    $_POST['SignActivity']['publish'] = 1;
			$param = $_POST['SignActivity'];
			$file = CUploadedFile::getInstance($model,'img');   //获得一个CUploadedFile的实例
			if(is_object($file) && get_class($file) === 'CUploadedFile')
			{
				$param['img'] = 'static/images/sign/'.time().rand(0,100).'.'.$file->extensionName;
			}else{
				unset($param['img']);
			}
			$model->attributes = $param;
			$model->update_time = time();
			if($model->save())
			{
				if(is_object($file) && get_class($file) === 'CUploadedFile'){
					$file->saveAs($model->img);    // 上传图片
				}
				$this->redirect($this->createUrl('index'));
			}else{
				print_r($model->getErrors());
			}
		}
		$this->render('update',array('model'=>$model,'create_mids'=>$create_mids));
	}
	
	public function actionGetCreater()
	{
	    $data = array();
	    $key = htmlspecialchars(trim(Yii::app()->request->getParam('term')));
	    if(!empty($key))
	    {
	        $sql = "SELECT * FROM member WHERE name LIKE '$key%'";
	        $result = Yii::app()->db->createCommand($sql)->queryAll();
	        if(!empty($result))
	        {
	            foreach($result as $value)
	            {
	                $data[] = $value['name'] . '(职位：'.$value['position'].',手机：'.$value['mobile'].',公司：'.$value['company'].',id:'.$value['id'].')';
	            }
	        }
	    }
	    echo json_encode($data);
	    exit();
	}
	
}
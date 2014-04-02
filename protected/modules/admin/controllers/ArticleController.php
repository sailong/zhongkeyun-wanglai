<?php
class ArticleController extends AdminController{
    
    public $nav='文章管理';
    public $service_member_id = 2;
    
    public function actionIndex()
    {
        $searchModel = new SearchForm();
        if(isset($_GET['SearchForm'])) $searchModel->attributes = $_GET['SearchForm'];
         
        $conditionArr = array();
        $condition = '';
        if($searchModel->keyword) $conditionArr[] = "title  like '%".$searchModel->keyword."%'";
    
        if($conditionArr) $condition = trim(implode(' AND ', $conditionArr),' AND ');
        $order = 'id DESC';
        $dataProvider = new CActiveDataProvider('Article',
                array(
                        'criteria' => array(
                                'order' => $order,
                                'condition' => $condition,
                        ),
                        'pagination' => array(
                                'pageSize' => 20
                        ),
                )
        );
        $data = array('dataProvider'=>$dataProvider);
         //评论数和点赞数
        $result  = $dataProvider->getData();
        if($result)
        {
            $article_ids = array();
            foreach ($result as $r)
            {
                $article_ids[] = $r->id;
            }
            $comment_count_arr = array();
            $mark_count_arr = array();
            $article_ids_str = implode("','", $article_ids);
           
            $comment_sql = "select article_id,count(id) as counts from article_comment group by article_id having article_id in('".$article_ids_str."')";
            $mark_sql = "select article_id,count(id) as counts from article_mark where type='".ArticleMark::TYPE_UP."' and `delete`='".ArticleMark::UP_DELETE_NO."' and article_id in('".$article_ids_str."') group by article_id";
           
            $commentobj_arr = Yii::app()->db->createCommand($comment_sql)->queryAll();
            $markobj_arr = Yii::app()->db->createCommand($mark_sql)->queryAll();
            
            foreach($commentobj_arr as $val){
                $comment_count_arr[$val['article_id']] = $val['counts'];
            }
            foreach($markobj_arr as $val){
                $mark_count_arr[$val['article_id']] = $val['counts'];
            }
            foreach($article_ids as $val){
                if(!$comment_count_arr[$val]){
                    $comment_count_arr[$val]=0;
                }
                if(!$mark_count_arr[$val]){
                    $mark_count_arr[$val]=0;
                }
            }
            unset($commentobj_arr,$markobj_arr,$article_ids);
            
        }
        $data['comment_count'] = $comment_count_arr;
        $data['mark_count'] = $mark_count_arr;
        $data['searchModel'] = $searchModel;
        //$data['publishArr'] = array(0=> '未发布',1=> '已发布');
        $this->render('index',$data);
    }
    
    public function actionDetail($id){
        $article_info=Article::model()->find(array(
                'select'=>'*',
                'condition'=>'id=:id',
                'params'=>array(':id'=>$id),
        ));
        $data['info'] = $article_info;
        $data['type'] = array(0=> '未发布',1=> '已发布');
        $this->render('articleinfo',$data);
    }

   
    /**
     * 添加操作
     */
    public function actionAdd()
    {
        $publish_arr = array(0=> '未发布',1=> '已发布');
        
        $this->render('add',array('publish_arr'=>$publish_arr));
    }
    
    //发送邮件
    public function actionAddDo()
    {

        $title = $_POST['title'];
        $content = $_POST['content'];
        $publish_uids = $_POST['publish_uids'];
    
        if(empty($title) || empty($content) || $publish_uids===''){
            $this->showMessage('操作失败',1,array('index'));
        }
        
        $create_mid = '';
        if(!empty($publish_uids))
        {
            preg_match('/id:(\d+)/', $publish_uids,$match);
            if(!empty($match[1]))
            {
                $create_mid = (int)$match[1];
            }
        }
        $img_url = '';
        if ($_FILES["share_pic"]["error"] <= 0)
        {
            $upload_path = dirname(Yii::app()->basePath);
            $ext_name =  pathinfo($_FILES["share_pic"]["name"], PATHINFO_EXTENSION);
            $img_url = "static/images/article/".time().rand(999,10000).'.'.$ext_name;
            move_uploaded_file($_FILES["share_pic"]["tmp_name"],$upload_path.'/'.$img_url);          
        }
        $content = str_replace('<img src="/attached/image/', '<img src="'.Yii::app()->request->hostInfo.'/attached/image/', $content);
        $now_time = time();
        $summary = mb_substr(preg_replace('/[\s|\&nbsp;]/', '', strip_tags($content)),0,50,'UTF-8');//mb_substr($content, 0, 50, 'UTF-8');
        $sql = "insert into article(title,create_mid,summary,share_pic,publish,publish_time,create_time,update_time) values('{$title}','{$create_mid}','{$summary}','{$img_url}','1','{$now_time}','{$now_time}','{$now_time}')";
        $command = Yii::app()->db->createCommand($sql);
        $ret = $command->execute();
        if($ret){
            $id = Yii::app()->db->getLastInsertID();
            $sql = "insert into article_content(article_id,content) values('{$id}','{$content}')";
            $command = Yii::app()->db->createCommand($sql);
            $command->execute();
        }else{
            $this->showMessage('操作失败',0,array('index'));
        }
        
    
        $this->showMessage('操作成功',1,array('index'));
    }
    
    /**
     * 修改操作
     */
    public function actionUpdate()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
		if($id)
		{
			$model = Article::model()->findByPk($id);
			//var_dump($model);die;
			$data['model'] = $model;
			$sql = "SELECT * FROM member WHERE id='".$model->create_mid."'";
			$result = Yii::app()->db->createCommand($sql)->queryRow();
			
			if(!empty($result))
			{
		        $data['create_mid'] = $result['name'] . '(职位：'.$result['position'].',手机：'.$result['mobile'].',公司：'.$result['company'].',id:'.$result['id'].')';			    
			}
		}else{
		    $this->showMessage('操作失败',0,array('index'));
		}
		//var_dump($data);
		$this->render('update',$data);
    }
    
    
    public function actionUpdateDo()
    {
    
        $title = $_POST['title'];
        $id = $_POST['id'];
        $content = $_POST['content'];
        $publish_uids = $_POST['publish_uids'];
    
        if(empty($title) || empty($content) || $publish_uids==='' || empty($id)){
            $this->showMessage('操作失败',0,array('index'));
        }
        $articleModel = Article::model()->findByPk($id);
        if(!empty($publish_uids))
        {
            preg_match('/id:(\d+)/', $publish_uids,$match);
            if(!empty($match[1]))
            {
                $articleModel->create_mid = $create_mid = (int)$match[1];
            }
        }
        $content = str_replace('<img src="/attached/image/', '<img src="'.Yii::app()->request->hostInfo.'/attached/image/', $content);
        $articleModel->title = $title;
        $articleModel->update_time = time();
        $articleModel->summary = mb_substr(preg_replace('/[\s|\&nbsp;]/', '', strip_tags($content)),0,50,'UTF-8');;//mb_substr($content, 0, 50, 'UTF-8');
        
        if ($_FILES["share_pic"]["error"] <= 0)
        {
            $upload_path = dirname(Yii::app()->basePath);
            $ext_name =  pathinfo($_FILES["share_pic"]["name"], PATHINFO_EXTENSION);
            $articleModel->share_pic = $img_url = "static/images/article/".time().rand(999,10000).'.'.$ext_name;
            move_uploaded_file($_FILES["share_pic"]["tmp_name"],$upload_path.'/'.$img_url);
        }
        
        if($articleModel->save()){
            $sql = "update article_content set content='{$content}' where article_id='{$id}'";
            $command = Yii::app()->db->createCommand($sql);
            $command->execute();
        }else{
            $this->showMessage('操作失败',0,array('index'));
        }
    
    
        $this->showMessage('操作成功',1,array('index'));
    }
    //删除操作
    public function actionDelete()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if(!$id) $this->showMessage('操作失败',0,array('index'));
        $model = Article::model()->findByPk($id);
        if(!$model) if(!$id) $this->showMessage('该数据不存在或已经被删除！',0,array('index'));
        //删除报名记录
        $model->state = '0';
        if($model->save())
        {
            $this->showMessage('操作成功',1,array('index'));
        }
        $this->showMessage('操作失败',0,array('index'));
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
<?php
class UploadController extends Controller
{
	
    public function actionUploadAvatar()
    {
    	ini_set('max_execution_time', 500);
    	/* $fn = (isset($_SERVER['HTTP_X_FILENAME']) ? $_SERVER['HTTP_X_FILENAME'] : false);
    	if ($fn) {
    		echo json_encode(array('error'=>1,'msg'=>'ok----ajax','imageurl'=>$url));die;
    		file_put_contents(YiiBase::getPathOfAlias('webroot').'/attachments/' . $fn,file_get_contents('php://input'));
    		$url = "http://wmp.huaxue114.com/attachments/$fn";
    		echo json_encode(array('error'=>1,'msg'=>'ok','imageurl'=>$url));
    		exit();
    	} */
    	//$image = Image::uploadDo('upphoto','avatar',array('s'=>200));
    	$image = Image::upload('upphoto','avatar',array('s'=>'200,200'),0,true);
    	$arr['code']=1;
    	if(!$image)
    	{
    		$arr['code']=0;
    	}elseif ($image==-2)
    	{
    		$arr['code']=-1;
    	}else
    	{
    		$arr['imageurl'] = Helper::getImage($image['filePath']);
    		$arr['avatar']  = $image['filePath'];
    	}
    	echo json_encode($arr);
		die;
    }
}
?>
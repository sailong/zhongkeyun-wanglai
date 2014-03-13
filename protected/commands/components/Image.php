<?php
/**
 * 文件上传
 */
class Image extends CController {
  
	 /**
	  * $type  :avatar  bg  other
	  */
     public static function uploadDo($name,$type='model',$sizeArr = array('s'=>500))
     {
     	$uploadedimage=CUploadedFile::getInstanceByName($name);
     	if(is_object($uploadedimage) && get_class($uploadedimage)==='CUploadedFile')
     	{
     		$extensionName = strtolower($uploadedimage->extensionName);
     		if(!$extensionName) $extensionName= 'jpg';
     		if(!in_array($extensionName, array('jpg','gif','jpeg','png')))
     		{
     			return false;
     		}
     		$type = strtolower($type);
     		
     		$filename = Image::createImageName(). '.' . $extensionName;
     		
     		//生成文件夹
     		list($uploaddir,$folder)=Image::getFolderPath($type);
     		$uploadfile=$uploaddir . $filename ;
     		$uploadedimage->saveAs($uploadfile);
     		//==开始压缩图片======================================
     		$filePath = $folder.'/'.$filename;	
     		if(is_array($sizeArr) && !empty($sizeArr))
     		{
     			foreach ($sizeArr as $picSizeStr=>$size)
     			{
     				$ret = Image::resizeImage($uploadfile,$size,$picSizeStr);
     				if($ret && strtolower($picSizeStr) == 's') //小图
     				{
     					$filePath = Image::getSmallImageName($filePath,$picSizeStr);
     				}
     			}
     		}
     		//===================================================
     		return array(
     					'filePath' => $filePath,
     				);
     	}
     }
     	//图片的等比缩放
     	public function resizeImage($image,$max= 300,$picSizeStr)
     	{
     		$jiaodu = 4;
     		if(!$max) $max = 300;
     		if(!$image) return false;
     		//取得源图片的宽度和高度
     		$size_src=getimagesize($image);
     		$w=$size_src['0'];
     		$h=$size_src['1'];
     		if($w< $max && $h< $max)
     		{
     			return false;
     		}
     		//因为PHP只能对资源进行操作，所以要对需要进行缩放的图片进行拷贝，创建为新的资源
     		//组织新文件并拷贝一份
     		$arr = explode('/', $image);
     		$length = count($arr);
     		$arr[$length-1] = Image::getSmallImageName(end($arr),$picSizeStr);
     		$c_image = implode('/', $arr);
     		if(!copy($image,$c_image)) return false;
     		//-----------------------------------------
     		$image=$c_image;
     		ini_set("gd.jpeg_ignore_warning", 1);
     		$src = Image::create($size_src,$image);
     		//根据最大值为300，算出另一个边的长度，得到缩放后的图片宽度和高度
     		if($w >=$h){
     			$w=$max;
     			$h=$h*($max/$size_src['0']);
     		}else{
     			$h=$max;
     			$w=$w*($max/$size_src['1']);
     		}
     		//声明一个$w宽，$h高的真彩图片资源
     		$n_image=imagecreatetruecolor($w, $h);
     		//关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
     		imagecopyresampled($n_image, $src, 0, 0, 0, 0, $w, $h, $size_src['0'], $size_src['1']);
     		ImageJpeg($n_image,$image);
     		return true;
     	}
     	
     	public static function create($info,$src)
     	{
     		switch ($info[2])
     		{
     			case 1:
     				$im=imagecreatefromgif($src);
     				break;
     			case 2:
     				$im=imagecreatefromjpeg($src);
     				break;
     			case 3:
     				$im=imagecreatefrompng($src);
     				break;
     		}
     		return $im;
     	}
     	/* xx_s.jpg 小图  
     	 * xx_m.jpg 中图
     	 * xx.jpg   原图
     	*/
     	public static function getSmallImageName($image,$picSizeStr='s')
     	{
     		return Str_Replace(".","_".$picSizeStr.".",$image);
     		
     		$arr = explode('/', $image);
     		$length = count($arr);
     		$arr[$length-1] = Str_Replace(".","_".$picSizeStr.".",end($arr));//Image::getSmallImageName(end($arr),$picSizeStr);
     		return implode('/', $arr);
     	}
     	/**
     	 * 获取图片存储路径
     	 * @param unknown $type
     	 */
     	public static function getFolderPath($type)
     	{
     		$root=YiiBase::getPathOfAlias('webroot');
     		$uploaddir=$root.'/attachments/';
     		$folder = $type.'/'.date('Ym/d');
     		if(!is_dir($uploaddir.$folder))
     		{
     			$subFolderarr = explode('/',$folder);
     			foreach($subFolderarr as $sub)
     			{
     				$uploaddir.= $sub.'/';
     				if(!is_dir($uploaddir)) mkdir($uploaddir);	
     			}
     		}else
     		{
     			$uploaddir = $uploaddir.$folder.'/';
     		}
     		return array($uploaddir,$folder);// $uploaddir  完整的文件夹路径   $folder 图片的相对地址
     	}
     	/* *
     	 * 生成新图片名
     	 * @return string
     	 */
     	public static function createImageName()
     	{
     		return substr(md5(uniqid()),mt_rand(0,20),6).mt_rand(10000,99999);
     	}
     	
     	//-0606------------goods picture-----------------------------------------------------------
     	public static function upload($name,$type='other',$sizeArr = array(),$maxSize = 0,$isDeleteOrginal=false)
     	{
     			$uploadedimage=CUploadedFile::getInstanceByName($name);
     			if(is_object($uploadedimage) && get_class($uploadedimage)==='CUploadedFile')
     			{
     				$extensionName = strtolower($uploadedimage->extensionName);
     				if(!$extensionName) $extensionName= 'jpg';
     				if(!in_array($extensionName, array('jpg','gif','jpeg','png')))
     				{
     					return false;
     				}
     				if($maxSize)
     				{
     					if($uploadedimage->getSize() > intval($maxSize) * 1024 *1024) return -2;
     				}
     				$type = strtolower($type);
     				$filename = Image::createImageName(). '.' . $extensionName;
     				//生成文件夹
     				list($uploaddir,$folder)=Image::getFolderPath($type);
     				$uploadfile=$uploaddir . $filename ;
     				if(!$uploadedimage->saveAs($uploadfile)) return;
     				//旋转图片
     				if(Helper::is_mobile())
     				{
     					$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
     					if(strpos($agent,'iphone'))
     					{ 
     						$exif = @exif_read_data($uploadfile);
     						if($exif && isset($exif['Orientation']))
     						{
     							$angle = 0;
     							switch ($exif['Orientation'])
     							{
     								case '3':
     										$angle = 180;
     										break;
     								case '6':
     										$angle = 270;
     										break;
     								case '8':
     										$angle = 90;
     										break;
     							}
     							if($angle) Image::flip($uploadfile, $uploadfile,$angle);
     						}
     						
     				 	}
     				} 
     			}else 
     			{
     				return;
     			}
     			//==开始压缩图片======================================
     			$filePath = $folder.'/'.$filename;
     			$isResize = 0;
     			if(is_array($sizeArr) && !empty($sizeArr))
     			{
     				foreach ($sizeArr as $picSizeStr=>$size)
     				{
     					$ret = Image::resizeImageBy($uploadfile,$size,$picSizeStr);
     					if($ret && strtolower($picSizeStr) == 's') //小图
     					{
     						$isResize = 1;
     						$filePath = Image::getSmallImageName($filePath,$picSizeStr);
     					}
     				}
     			}
     			//===================================================
     			if($isDeleteOrginal && $isResize)
     			{
     				//删除原图
     				unlink($uploadfile);
     			}
     			return array(
     					'filePath' => $filePath,
     			);
     	}
     	
     	public static function resizeImageBy($image,$size,$picSizeStr)
     	{
     		if(!$size) return;
     		$arr = explode('/', $image);
     		$length = count($arr);
     		$arr[$length-1] = Image::getSmallImageName(end($arr),$picSizeStr);
     		$d_photo = implode('/', $arr);
     		//$d_photo = Image::getSmallImageName($image,$picSizeStr);
     		$size_src=getimagesize($image);
		    $o_width=$size_src['0'];
		    $o_height=$size_src['1'];
		    $temp_img = Image::create($size_src, $image);
     		$sizeValue = explode(',', $size);
     		if(is_array($sizeValue) && count($sizeValue) == 2)
     		{
     			list($width,$height) = $sizeValue;                       
     			$x = $y = 0;
     			//判断处理方法
     			if($width>$o_width || $height>$o_height) //原图宽或高比规定的尺寸小,进行放大  裁剪
     			{   
     				list($newwidth,$newheight) = Image::getImageNewWh($o_width, $o_height, $width, $height);
     			} 
     				//原图宽与高都比规定尺寸大,进行压缩后裁剪
     				if($o_height*$width/$o_width>$height)
     				{                                           //先确定width与规定相同,如果height比规定大,则ok
     					$newwidth=$width;
     					$newheight=$o_height*$width/$o_width;
     					$x=0;
     					$y=($newheight-$height)/2;
     				}else
     				{                                           //否则确定height与规定相同,width自适应
     					$newwidth=$o_width*$height/$o_height;
     					$newheight=$height;
     					$x=($newwidth-$width)/2;
     					$y=0;
     				}	
     		
     			ini_set("gd.jpeg_ignore_warning", 1);
     			//缩略图片
     			$new_img = imagecreatetruecolor($newwidth, $newheight);
     			imagecopyresampled($new_img, $temp_img, 0, 0, 0, 0, $newwidth, $newheight, $o_width, $o_height);
     			ImageJpeg($new_img , $d_photo);
     			imagedestroy($new_img);
     			if($x || $y)
     			{
     				//$temp_img = imagecreatefromjpeg($d_photo);
     				$size_src = getimagesize($d_photo);
     				$temp_img = Image::create($size_src,$d_photo);
     				
     				$o_width  = imagesx($temp_img);                                //取得缩略图宽
     				$o_height = imagesy($temp_img);                                //取得缩略图高
     				//裁剪图片
     				$new_imgx = imagecreatetruecolor($width,$height);
     				imagecopyresampled($new_imgx,$temp_img,0,0,$x,$y,$width,$height,$width,$height);
     				imagejpeg($new_imgx , $d_photo);
     				imagedestroy($new_imgx);
     			}
     			return true;
     		}else 
     		{
     				$width = $sizeValue[0];
     				if($o_width <=$width) return;
     				$newwidth  = $width;
     				$newheight = $o_height*($width/$o_width);
     				//声明一个$w宽，$h高的真彩图片资源
     				$new_img=imagecreatetruecolor($newwidth, $newheight);
     				//关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
     				imagecopyresampled($new_img, $temp_img, 0, 0, 0, 0, $newwidth, $newheight, $o_width, $o_height);
     				ImageJpeg($new_img,$d_photo);
     				imagedestroy($new_img);
     				return true;
     		}
     	}
     	
     	function getImageNewWh($x,$y,$width,$height)
     	{
     		if($x>$y)
     		{
     			$thumbw = $x*$height/$y; // 期望的目标图宽
     			$thumbh = $height; // 期望的目标图高
     		}else
     		{
     			//放大200%,缩小雷同
     			$thumbw = $width; // 期望的目标图宽
     			$thumbh = $y*$width/$x; // 期望的目标图高
     		}
     		
     		if($thumbw<$width)
     		{
     			$x = $thumbw;
     			$thumbw = $width; // 期望的目标图宽
     			$thumbh = $thumbh*$width/$x; // 期望的目标图高
     		}
     		
     		if($thumbh<$height)
     		{
     			$y = $thumbh;
     		
     			$thumbw = $thumbw*$height/$y; // 期望的目标图高
     			$thumbh = $height; // 期望的目标图宽
     		}
     		
     		return array($thumbw,$thumbh);
     	}
     	
     	
     	public static function  flip($filename,$src,$degrees = 180)
     	{
     		//读取图片
     		$data = @getimagesize($filename);
     		if($data==false)return false;
     		//读取旧图片
     		switch ($data[2]) {
     			case 1:
     				$src_f = imagecreatefromgif($filename);break;
     			case 2:
     				$src_f = imagecreatefromjpeg($filename);break;
     			case 3:
     				$src_f = imagecreatefrompng($filename);break;
     		}
     		if($src_f=="")return false;
     		$rotate = @imagerotate($src_f, $degrees,0);
     		if(!imagejpeg($rotate,$src,100))return false;
     		@imagedestroy($rotate);
     		return true;
     	}
}
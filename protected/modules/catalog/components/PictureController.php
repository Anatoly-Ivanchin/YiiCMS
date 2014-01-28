<?php

class PictureController extends CController
{

	protected function getPictureTypes()
	{
		return array();
	}
	
	protected function getPictureType($type)
	{
		$types = $this->getPictureTypes();
		return $types[$type];
	}
	
    public function actionPicture()
	{
		if(Yii::app()->session->itemAt('picturePath') == null)
		{
			if(isset($_GET['id']))
			{
				$item = $this->loadItem();
				$file=$item->getImageUrl('small');
			}     
		}
		else
		{
			$path = $this->getPictureType('small');
		    $file = $path['path'].Yii::app()->session->itemAt('picturePath');
		}
		$this->renderPartial('catalog.views.items.picture',array('file'=>$file));
	}
	
	public function actionUpload()
	{
		if(isset($_GET['id']))
		    $item=$this->loadItem();
		Yii::app()->session->sessionID = $_POST['PHPSESSID'];
		Yii::app()->session->init();
		
		if(isset($_FILES['Filedata']))
		{
			$oldName = $_FILES['Filedata']['name'];
			$arr = explode('.',$oldName);
			$size = $_FILES['Filedata']['size'];
			$fileName = md5($oldName.$size).'.'.$arr[1];
			
			if ($this->createImageSet($_FILES['Filedata']['tmp_name'], $fileName))
			{
				Yii::app()->session->add('picturePath',$fileName);
			    echo 'true';
				return true;
			}
			else
			    echo 'error';
		}
		else
		    echo 'error';
	}
	
	protected function createImageSet($image,$fileName)
	{
		Yii::import('application.extensions.ImageCropper');
		$cropper=new ImageCropper();
		
		foreach ($this->getPictureTypes() as $type=>$pic)
		{file_put_contents($_SERVER['DOCUMENT_ROOT'].'\test.txt','work');
			$filePath = $_SERVER['DOCUMENT_ROOT'].$pic['path'].$fileName;
			if (!isset($pic['w']) && !isset($pic['h']))
			{
				if (!move_uploaded_file($image,$filePath))
				    return false;
			}
			else
			{
			    if (!$cropper->resize_and_crop($image, $filePath, $pic['w'], $pic['h']))
			        return false;
			}
		}
		    
		return true;
	}
	
}
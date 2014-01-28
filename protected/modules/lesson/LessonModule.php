<?php

class LessonModule extends CBrick 
{
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'lesson.models.*',
			'lesson.components.*',
		));
		
		$this->title='Открытый урок';
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
	
   	public function getAdminlinks()
	{
		return array(
		   'Просмотр заявок'=>array('/lesson/enroll/admin'),
		   'Управление расписанием'=>array('/lesson/schedule/admin'),
		   'Управление списком школ'=>array('/lesson/schools/admin'),
		);
	}
	
	public function getMenuItems()
	{
		return array('lesson_add'=>array(
			'title'=>'Открытый урок',
			'url'=>array('/lesson/enroll/create'),
		));
	}
}

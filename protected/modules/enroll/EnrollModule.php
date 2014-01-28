<?php

class EnrollModule extends CBrick 
{
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'enroll.models.*',
			'enroll.components.*',
		));
		
		$this->title='Заявки';
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
		   'Просмотр заявок'=>array('/enroll/enroll/admin'),
		   'Управление списком программ'=>array('/enroll/programms/admin'),
		   'Управление списком "Части дня"'=>array('/enroll/daypart/admin'),
		   'Управление списком "Откуда Вы о нас узнали"'=>array('/enroll/ads/admin'),
		);
	}
	
	public function getMenuItems()
	{
		return array('enroll_add'=>array(
			'title'=>'Запись on-line',
			'url'=>array('/enroll/enroll/create'),
		));
	}
}

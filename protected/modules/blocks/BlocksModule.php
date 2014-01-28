<?php

class BlocksModule extends CBrick
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'blocks.models.*',
			'blocks.components.*',
		));
		
		$this->title='Контентные блоки';
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
	
	protected function getCacheDependency()
	{
		return new CDbCacheDependency('select max(update_date) from `Block`');
	}
	
	public function getRoutes()
	{
		return array(
		    'blocks/managment'=>array('blocks/default/admin'),
		);
	}
	
	public function getAdminLinks()
	{
		return array('Управление контентными блоками'=>array('/blocks/default/admin'));
	}
}
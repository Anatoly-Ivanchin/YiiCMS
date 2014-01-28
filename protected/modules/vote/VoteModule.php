<?php

class VoteModule extends CBrick
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'vote.models.*',
			'vote.components.*',
		));
		
		$this->title='Опрос';
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
		    'vote/managment'=>array('vote/question/admin'),
		    'vote/<id:\d+>/update'=>array('vote/question/update'),
		    'vote/<id:\d+>/delete'=>array('vote/question/delete'),
		    
		    'vote/<qid:\d+>/answers'=>array('vote/answer/admin'),
		    'vote/answer/<id:\d+>/update'=>array('vote/answer/update'),
		    'vote/answer/<id:\d+>/delete'=>array('vote/answer/delete'),
		    
		);
	}
	
	public function getAdminLinks()
	{
		return array(
			'Управление опросами'=>array('/vote/question/admin')
		);
	}
}

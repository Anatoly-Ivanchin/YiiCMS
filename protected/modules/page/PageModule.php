<?php

class PageModule extends CBrick 
{
	protected $_pages;
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'page.models.*',
			'page.components.*',
		));
		$this->title='Страницы';
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
	
	protected function getPages()
	{
		if($this->_pages==null)
		{
			$this->_pages=Page::model()->findAll();
		}
		return $this->_pages;
	}
	
	protected function getCacheDependency()
	{
		return new CDbCacheDependency('select max(updateTime) from page');
	}
	
	protected function generateRoutes()
	{
		$rules=array();
		
		$rules['page/managment']=array('page/default/admin');
		$rules['page/create']=array('page/default/create');
		$pages=$this->getPages();
		
		foreach ($pages as $page)
		{
			$url=$page->url;
			if($url==null)
				$rules['/']=array('page/default/show','defaultParams'=>array('url'=>null));
			else
				$rules['<url:'.$url.'>']=array('page/default/show');
		}
		krsort($rules);
		return $rules;
	}

	protected function generateMenu()
	{
		$items = array();
		$pages = $this->getPages();
		foreach ($pages as $page)
		{
			$items['cp_'.$page->id]=array(
			    'title'=>$page->title,
			    'url'=>($page->url==null)?array('/page/default/show'):array('/page/default/show','url'=>$page->url)
			);
		}
		return $items;
	}

	public function getAdminLinks()
	{
		return array(
		   'Управление страницами'=>array('/page/default/admin'),
		);
	}
}

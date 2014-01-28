<?php

class NewsModule extends CBrick 
{
	/**
	 *
	 * Enter description here ...
	 * @var NewsBlock
	 */
	protected $_newsBlocks=null;
	
	public $galleryMode=false;
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'news.models.*',
			'news.components.*',
		));
		
		$this->title='Новости';
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
	
	protected function getBlocks()
	{
		if($this->_newsBlocks==null)
		{
			$this->_newsBlocks=NewsBlock::model()->findAll();
		}
		return $this->_newsBlocks;
	}
	
	protected function generateRoutes()
	{
		$rotes = array(
		    'newsblock/managment'=>'news/block/admin',
		    
		    'news/<id:\d+>/update'=>'news/item/update',
		    'news/<id:\d+>/delete'=>'news/item/delete',
		);
		
		foreach($this->getBlocks() as $block)
		{
			$rotes['newsblock/<url:'.$block->url.'>/update']='news/block/update';
			$rotes['newsblock/<url:'.$block->url.'>/delete']='news/block/delete';
			$rotes['<url:'.$block->url.'>']='news/block/show';
			$rotes['newsblock/<block:'.$block->url.'>/managment']='news/item/admin';
			$rotes['<url:'.$block->url.'>/<id:\d+>']='news/item/show';
		}
		return $rotes;
	}
	
	protected function getCacheDependency()
	{
		return new CDbCacheDependency('select max(updateTime) from News_Block');
	}
	
    protected function generateMenu()
	{
		$menu = array();
		foreach ($this->getBlocks() as $block)
		{
			$menu['nb_'.$block->url] = array(
				'title'=>$block->title,
				'url'=>array('/news/block/show','url'=>$block->url),
			);
		}
		return $menu;
	}

	protected function generateAdminlinks()
	{
		$menu = array(
		   'Управление блоками новостей'=>array('/news/block/admin'),
		);
		foreach ($this->getBlocks() as $block)
		{
			$menu['Управление новостным блоком "'.$block->title.'"'] = array('/news/item/admin','block'=>$block->url);
		}
		return $menu;
	}
}

<?php

class FaqModule extends CBrick 
{
	protected $_blocks;
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'faq.models.*',
			'faq.components.*',
		));
		$this->title='Сообщения пользователей';
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
		if($this->_blocks==null)
		{
			$this->_blocks=FaqBlock::model()->findAll();
		}
		return $this->_blocks;
	}
	
	protected function getCacheDependency()
	{
		return new CDbCacheDependency('select max(updateTime) from Faq_Block');
	}
	
	protected function generateRoutes()
	{
		$rules=array();
		$blocks=$this->getBlocks();
		
		foreach ($blocks as $block)
		{
			$rules['<url:'.$block->url.'>']='faq/block/show';
			$rules['<url:'.$block->url.'>/<id:\d+>']='faq/item/show';
		}
		return $rules;
	}

	protected function generateMenu()
	{
		$items = array();
		$blocks = $this->getBlocks();
		
		foreach ($blocks as $block)
		{
			$items['faq_'.$block->id]=array(
			    'title'=>$block->title,
			    'url'=>array('/faq/block/show','url'=>$block->url)
			);
		}
		return $items;
	}

	public function getAdminLinks()
	{
		$links= array(
		   'Управление разделами'=>array('/faq/block/admin'),
		);
		
		$blocks = $this->getBlocks();
		
		foreach ($blocks as $block)
		{
			$links['"'.$block->title.'"']=array('/faq/item/admin','block'=>$block->id);
		}
		return $links;
	}
}

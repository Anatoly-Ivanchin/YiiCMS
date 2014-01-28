<?php

class CatalogModule extends CBrick
{
	public $cssFile=null;
	public $itemsPrice=true;
	public $itemsCount=true;
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'catalog.models.*',
			'catalog.components.*',
		));
		
		$this->title='Каталог';
		
		if ($this->cssFile == null)
		{
			$baseDir = dirname(__FILE__);
			$this->cssFile = Yii::app()->getAssetManager()->publish($baseDir.DIRECTORY_SEPARATOR.'css').'/catalog.css';
		}
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
		return new CDbCacheDependency('select max(updateDate) from Catalog_Categories');
	}

	protected function generateRoutes()
	{
		$rules= array(
		    'catalog/categories'=>array('catalog/categories/admin'),
			'catalog/categories/<cat:\d+>/params'=>array('catalog/params/admin'),
		    'catalog/items'=>array('catalog/items/admin'),
		    'catalog/search'=>array('catalog/default/search'),
		);
		
		$rules= array_merge($rules,$this->getCategoryRules());
		return $rules;
	}
	
	protected function getCategoryRules($cat=null,$path=null)
	{
		$rules = array();
		if($cat==null)
		{
		    $cat = Categories::model()->find('isnull(parentId)');
		    $rules['<cat:'.$cat->url.'>']=array('catalog/default/itemslist');
		    $path=$cat->url.'/';
		}
		if (count($cat->items)>0)
	        foreach ($cat->items as $item)
		    {
			    $rules[$path.'<item:'.$item->url.'>']=array('catalog/default/item');			    
		    }
		if (count($cat->children)>0)
	        foreach ($cat->children as $child)
		    {
			    $rules[$path.'<cat:'.$child->url.'>']=array('catalog/default/itemslist');
			    $rules = array_merge($rules,$this->getCategoryRules($child,$path.$child->url.'/'));			    
		    }
		return $rules;
	}
	
	protected function generateMenu()
	{
		return $this->menu();
	}
	
	protected function menu($cat=null,$url=null)
	{
		$res=array();
		if($cat==null)
		{
		    $cat = Categories::model()->find('isnull(parentId)');
		    if($cat==null)
		        return $res;
		    $item=array(
		        'title'=>$cat->title,
		        'url'=>'/'.$cat->url,
		    );
		    $subItems=$this->menu($cat,'/'.$cat->url);
		    if(count($subItems)>0)
			    $item['children']=$subItems;
		    $res['cat_f'.$cat->id] =$item;
		}
		else
		{
			foreach($cat->children as $child)
			{
				$item=array(
				    'title'=>$child->title,
				    'url'=>$url.'/'.$child->url,
				);
				$subItems=$this->menu($child,$url.'/'.$child->url);
				if(count($subItems)>0)
					$item['children']=$subItems;
				$res['cat_f'.$child->id]=$item;				
			}
		}
		return $res;
	}
	
	public function getAdminLinks()
	{
		return array(
		    'Управление разделами каталога'=>array('/catalog/categories/admin'),
		    'Управление элементами каталога'=>array('/catalog/items/admin')
		);
	}
}

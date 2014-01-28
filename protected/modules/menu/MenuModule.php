<?php

class MenuModule extends CBrick
{
	protected $menu=array();
	protected $activeItems=array();
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'menu.models.*',
			'menu.components.*',
		));
		
		$this->title='Меню';
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
	
	protected function generateAdminlinks()
	{
		$links = array(
		   'Управление меню'=>array('/menu/menu/admin'),
		);
		foreach (Menu::model()->findAll() as $menu)
		{
			$links[$menu->description] = array('/menu/menuitems/admin','ident'=>$menu->ident);
		}
		return $links;
	}
	
	protected function getCacheDependency()
	{
		return new CDbCacheDependency('select max(updateTime) from Menu_Items');
	}
	
	public function getModulesList()
	{
		$modules=array();
		foreach(Yii::app()->getModules() as $id=>$m)
		{
			$moduleObj=Yii::app()->getModule($id);
			if ($moduleObj instanceof CBrick && count($moduleObj->getMenuItems()))
			    $modules[$id]=$moduleObj->getTitle();
		}
		return $modules;
	}
	
	public function getModuleMenuItems($mid, $items=null, $level=0)
	{
		$res=array();
		$level++;
		if($items==null)
		{
			$moduleObj=Yii::app()->getModule($mid);
			$items=$moduleObj->getMenuItems();
		}	
		foreach ($items as $id=>$item)
		{
			$label=null;
			for ($i=0; $i<$level-1; $i++)
			{
				$label.=" - ";
			}
			$res[$id]=$label.$item['title'];
			if(isset($item['children']) && is_array($item['children']) && count($item['children']))
				$res=array_merge($res, $this->getModuleMenuItems($mid,$item['children'],$level));		
		}
		return $res;
	}
	
	/* Отдаем уровень меню */
	
	public function getMenuLevel($ident, $level=1, $deep=1)
	{
		$menu=$this->getMenuData($ident);
		$activeItems=$this->activeItems[$ident];
		
		if($level==1)
			$menuLevel=$menu;
	
		if(count($activeItems)<$level-1)
			return array();
		elseif (count($activeItems)>$level-1) {
			$activeItems=array_slice($activeItems, 0,$level-1);
		}
		
		$menuLevel=$menu;
		if(is_array($activeItems))
		foreach ($activeItems as $id)
		{
			$nextLevel=$menuLevel[$id]['children'];
			if(is_array($nextLevel) && count($nextLevel))
				$menuLevel=$nextLevel;
			else
				return array();			
		}
		return $this->applyDeep($menuLevel,$ident,$deep);
	}
	
	protected function applyDeep($items,$ident,$deep,$currentIteration=1)
	{
		$res=array();
		foreach ($items as $k=>$item)
		{
			$this->isActive($item, $ident);
			if(isset($item['children']))
				if($currentIteration<$deep && count($item['children']))
					$item['children']=$this->applyDeep($item['children'], $ident, $deep,$currentIteration+1);
				else
					unset($item['children']);
				
			$res[$k]=$item;
		}
		return $res;
	}
	
	/* Собираем меню */
	
	public function getMenuData($ident)
	{
		if(!isset($this->menu[$ident]))
		{
			$dynamicItems=array();
			$commonCacheId='Menu_'.$ident;
			$itemsCachId=$commonCacheId.'_items';
			$dataCachId=$commonCacheId.'_data';
			
			$data=Yii::app()->cache->get($itemsCachId);
			$dynamicItems=Yii::app()->cache->get($dataCachId);
			
			if($data===false || $dynamicItems===false)
			{
				$dynamicItems=array();
				$data=$this->iterateMenuLevel($ident,null,$dynamicItems);
				$dependency=$this->getCacheDependency();
				
				Yii::app()->cache->set($itemsCachId, $data, null, $dependency);
				Yii::app()->cache->set($dataCachId, $dynamicItems, null, $dependency);
			}
			
			$this->menu[$ident]=$this->apllyRules($data, $dynamicItems);
		}
		return $this->menu[$ident];
	}
	
	protected function iterateMenuLevel($ident,$pid=null,&$dynamicsCoord=array(),$parentsCoord=array())
	{
		$cond=array('menuId'=>$ident,'parentId'=>$pid);
		$cri=new CDbCriteria();
		$cri->addColumnCondition($cond);
		$cri->order='priority';
		
		$items=MenuItems::model()->findAll($cri);
		
		$data=array();
		foreach ($items as $i=>$item)
		{
			$coord=array_merge($parentsCoord,array($i));
			$itemData=array(
				'title'=>$item->title,
				'description'=>$item->description,
				'inNewWindow'=>$item->inNewWindow,
				'coord'=>$coord
			);
			switch ($item->type)
			{
				case MenuItems::TYPE_STATIC:
					$itemData['url']=$item->url;
					break;
				case MenuItems::TYPE_MODULE_PAGE:
					$dynamicsCoord[]=array(
						'data'=>array(
							'module'=>$item->module,
							'page'=>$item->page,
							'withChildren'=>$item->withChildren
						),
						'coord'=>$coord
					);
					break;
			}
			if($item->pattern)
			{
				$itemData['pattern']=$item->pattern;
			}
			if($item->canHasSubMenu())
			{
				$children=$this->iterateMenuLevel($ident,$item->id,$dynamicsCoord,$coord);
				if(count($children)>0)
					$itemData['children']=$children;
			}
			if($item->hasImage('preview'))
			{
				$itemData['icon']=$item->getImageUrl('preview');
			}
			$data[]=$itemData;
		}
		return $data;
	}
	
	protected function apllyRules($menuItems,$dynamicElements)
	{
		foreach ($dynamicElements as $element)
		{
			$data=$element['data'];
			
			$itemVar='$menuItems['.implode(']["children"][', $element['coord']).']';
			eval('if(isset('.$itemVar.'))$item=&'.$itemVar.';');
			
			if(!isset($item))
				continue;
				
			$dynamicData=$this->getDynamicData($data['module'],$data['page']);			
			if($dynamicData==false)
			{
				eval('unset('.$itemVar.');');
			}
			else
			{
				$item['url']=$dynamicData['url'];
				if($data['withChildren'] && isset($dynamicData['children']) && count($dynamicData['children'])>0)
					$item['children']=$this->applayCoord($dynamicData['children'],$item['coord']);
			}
		}
		return $menuItems;
	}
	
	protected function applayCoord($data,$coord=array())
	{
		$res=array();
		foreach($data as $k=>$v)
		{
			$v['coord']=$coord;
			$v['coord'][]=$k;
			if(isset($v['children']))
				$this->applayCoord($v['children'],$v['coord']);
			$res[$k]=$v;
		}
		return $res;
	}
	
	protected function getDynamicData($module,$page)
	{
		$module=Yii::app()->getModule($module);
		if($module)
		{
			$menu=$module->getMenuItems();
			return $this->proccessModuleMenu($menu, $page);
		}
		else return false;
	}
	
	protected function proccessModuleMenu($items,$page)
	{
		$nextLevel=array();
		foreach ($items as $id=>$item)
		{
			if($page==$id)
				return $item;
			if(isset($item['children']))
				$nextLevel=array_merge($nextLevel,$item['children']);
		}
		if(count($nextLevel)>0)
			$this->proccessModuleMenu($nextLevel, $page);
		else
			return false;
	}
	
	/* Активный элемент */
	
	public function isActive($item,$ident)
	{
		if($this->isAlreadyActive($item, $ident))
			return true;
		$isActive=false;
		$req=Yii::app()->getRequest();
		if(isset($item['url']))
		{
			$itemUrl=$item['url'];
			switch (gettype($itemUrl))
			{
				case 'string':
					$isActive=$this->checkStatic($itemUrl,$req);
					break;
				case 'array':
					$isActive=$this->checkRule($itemUrl,$req);
					break;
			}
		}
		if(isset($item['pattern']))
		{
			$isActive=$this->checkByPattern($item['pattern'], $req);
		}
		if($isActive)
		{
			$this->activeItems[$ident]=$item['coord'];
		}
		return $isActive;		
	}
	
	protected function isAlreadyActive($item,$ident)
	{
		if(!is_array($this->activeItems[$ident]) || count($this->activeItems[$ident])<count($item['coord']))
			return false;
		foreach ($item['coord'] as $k=>$v)
			if ($v!=$this->activeItems[$ident][$k])
				return false;
		return true;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $url
	 * @param CHttpRequest $req
	 */
	protected function checkStatic($url,$req)
	{
		if(strpos($url, '/')==0)
		{
			return $url==$req->getUrl();
		}elseif (preg_match('/^(http|https):\/\/.+/i', $url, $matches))
		{
			return $url==$req->getHostInfo($matches[1]).$req->getUrl(); 
		}else
			return false;
	}
	
	/**
	 * @param array $rule
	 * @param CHttpRequest $req 
	 */
	protected function checkRule($rule,$req)
	{
		$route=trim($rule[0],'/');
		$params=array_splice($rule,1);
		
		$controllerId=Yii::app()->getController()->getUniqueId();
		$actionId=Yii::app()->getController()->getAction()->id;
	
		if(strpos($route,'/')!==false)
			$matched=$route===$controllerId.'/'.$actionId;
		else
			$matched=$route===$controllerId;

		if($matched && count($params)>0)
		{
			foreach($params as $name=>$value)
			{
				if(!isset($_GET[$name]) || $_GET[$name]!=$value)
					return false;
			}
			return true;
		}
		else
			return $matched;
	}
	
	/**
	 * @param string $pattern
	 * @param CHttpRequest $req
	 */
	protected function checkByPattern($pattern,$req)
	{
		$currUrl=$req->getUrl();
		return preg_match('/'.$pattern.'/i', $currUrl, $m);
	}
}
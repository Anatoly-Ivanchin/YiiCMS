<?php

class DisplayMenu extends cmsWidget
{
	public $ident;
	public $dropDown=true;
	public $startLevel=1;
	public $deep=8;
	public $effectOptions=array();
	
	protected function getMenuId()
	{
		return $this->ident.'_menu_l'.$this->startLevel;
	}
	
	protected function getMenuItemId($item)
	{
		$coord=implode('', $item['coord']);
		return $this->getMenuId().'_k'.$coord;
	}

	public function run()
	{
		$this->regScripts();
		
		$items=$this->getItems();
		
		if(count($items)==0)
			return;
		
		$content=$this->render('menuView',array('items'=>$items,'level'=>$this->startLevel),true);
		echo CHtml::tag('div',array('id'=>$this->getMenuId()),$content);
	}
	
	protected function regScripts()
	{
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$js='$("#'.$this->getMenuId().' .menu_item").hover(function(){$(this).addClass("hover");},function(){$(this).removeClass("hover");});';
		if($this->dropDown) {
			$baseDir = dirname(__FILE__);
			$assets = Yii::app()->getAssetManager()->publish($baseDir.DIRECTORY_SEPARATOR.'js/dropdown.js');

			$cs->registerScriptFile($assets,CClientScript::POS_END);
        
			$opts=CJavaScript::jsonEncode($this->effectOptions);
        	$js.='$("#'.$this->getMenuId().'").yDropdown('.$opts.');';
		}
		$cs->registerScript($this->getMenuId(),$js,CClientScript::POS_END);
	}
	
	protected function getItems()
	{
		return Yii::app()->getModule('menu')->getMenuLevel($this->ident,$this->startLevel,$this->deep);
	}
	
	protected function isActive($item)
	{
		return Yii::app()->getModule('menu')->isActive($item,$this->ident); 
	}
	
	protected function linkHtmlOption($item, $default=array())
	{
		$classes=isset($default['class'])?array($default['class']):array();
		if(!isset($item['url']))
		{
			$default['onclick']='event.preventDefault();';
			$classes[]='group';
		}
		if($item['inNewWindow'])
			$default['target']='_blank';
		if($item['description'])
			$default['title']=$item['description'];
		if($this->isActive($item))
			$classes[]='active';
		if(count($classes)>0)
			$default['class']=implode(' ', $classes);
		return $default;
	}
}
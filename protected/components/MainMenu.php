<?php

class MainMenu extends CWidget
{

	public function run()
	{
		$this->render('mainMenu',array('items'=>$this->getItems()));
	}
	
	protected function getItems()
	{
		return array(
		    array(
		        'title'=>'Главная',
		        'url'=>'/',
		        'tooltip'=>'Перейти на главную страницу',
		        'icon'=>'main.png',		    
		    ),
		    array(
		        'title'=>'О компании',
		        'url'=>'/about',
		        'tooltip'=>'Перейти на страницу О компании',
		        'icon'=>'about.png',		    
		    ),
		    array(
		        'title'=>'Продукция',
		        'url'=>'/catalog',
		        'tooltip'=>'Просмотр каталога продукции',
		        'icon'=>'cat.png',		    
		    ),
		    array(
		        'title'=>'Контакты',
		        'url'=>'/contacts',
		        'tooltip'=>'Перейти на страницу',
		        'icon'=>'contacts.png',		    
		    ),
		);
	}
	
	public function isActive($itemUrl)
	{
		$path = Yii::app()->getRequest()->getUrl();
		
		if ($path==$itemUrl)
		    return true;

		if ($itemUrl=='/')
		    return false;
		  
		if (strlen($path)<strlen($itemUrl))
		{
			return false;
		}
		else
		{
			if(substr($path,0,strlen($itemUrl))==$itemUrl)
			    return true;
			else
			    return false;
		}
	}
}
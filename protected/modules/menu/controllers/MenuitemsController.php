<?php

class MenuitemsController extends CController
{
	const PAGE_SIZE=10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='admin';
	public $layout='application.views.layouts.admin';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;
	private $_menu;
	
	public function actions()
	{
		return array(
			'delete'=>array(
				'class'=>'application.components.actions.SimpleDelete',
				'pageTitle'=>'Удаление пункта меню',
				'successMsg'=>'Пункт меню удален',
				'deleteMsg'=>'Вы действительно хотите удалить пункт меню \"{$model->title}\"?',
		
			),
			'update'=>array(
				'class'=>'application.components.actions.SimpleEdit',
				'pageTitle'=>'Редактирование пункта меню',	
			),
			'create'=>array(
				'class'=>'application.components.actions.SimpleCreate',
				'pageTitle'=>'Добавление пункта меню',
				'onLoad'=>'onLoadNewModel',
			),
			'upload'=>array(
				'class'=>'application.components.actions.UploadAction'
			),
		);
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
			'ajaxOnly-admin,upload',
			array('jQueryAjaxFilter')
		);
	}
	
	public function behaviors()
	{
		return array(
			'breadCrumbs'=>'BreadCrumbsBehavior',
			'ar'=>'AjaxRender',
			'order'=>array(
		        'class'=>'application.components.behaviors.OrderBehavior',
		        'modelClass'=>'MenuItems',
		        'modelListCondition'=>'menuId="{$this->getModel()->menuId}" AND parentId{$this->getModel()->parentId==null?" is null":" =".$this->getModel()->parentId}',
		    ),
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'list' and 'show' actions
				'actions'=>array('upload'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$menu=$this->loadMenu();
		
		$this->breadCrumbs=array(
			'Управление сайтом'=>array('/users/default/siteManagment'),
			'Управление меню'=>array('menu/admin'),
		);
		
		$this->updatePriority();

		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array('menuId'=>$menu->ident));
		
		if(isset($_GET['pid']))
		{
			$parent=$this->loadModel($_GET['pid']);
			$criteria->addColumnCondition(array('parentId'=>$parent->id));
		}
		else
			$criteria->addCondition('parentId IS NULL');

		$pages=new CPagination(MenuItems::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('MenuItems');
		$sort->defaultOrder='priority';
		$sort->applyOrder($criteria);

		$models=MenuItems::model()->findAll($criteria);

		$this->widget('FormWindow');
		
		$this->renderAjax('admin',array(
			'models'=>$models,
			'pages'=>$pages,
			'sort'=>$sort,
			'menu'=>$menu,
			'parent'=>$parent,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadModel($id=null,$new=false)
	{
		if($this->_model===null)
		{
			if($new)
				$this->_model=new MenuItems();
			if($id!==null || isset($_GET['id']))
				$this->_model=MenuItems::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
	
	public function loadMenu()
	{
		if($this->_menu===null)
		{
			if(isset($_GET['ident']))
				$this->_menu=Menu::model()->findbyPk($_GET['ident']);
			if($this->_menu===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_menu;
	}
	
	public function actionModulepages()
	{
		foreach ($this->getModule()->getModuleMenuItems($_POST['mid']) as $id=>$text)
		{
			echo CHtml::tag('option', array('value'=>$id), $text);
		}
	}
	
	public function onLoadNewModel($data)
	{
		$model=$data['model'];
		$moduleList=$this->getModule()->getModulesList();
		$moduleListIds=array_keys($moduleList);
		$model->module=$moduleListIds[0];
		$model->menuId=$_GET['ident'];
		$model->parentId=$_GET['pid'];
		return array('model'=>$model);
	}
}

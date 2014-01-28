<?php

class MenuController extends CController
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
	
	public function actions()
	{
		return array(
			'delete'=>array(
				'class'=>'application.components.actions.SimpleDelete',
				'pageTitle'=>'Удаление меню',
				'successMsg'=>'Меню удалено',
				'deleteMsg'=>'Вы действительно хотите удалить меню \"{$model->description}\"?',
		
			),
			'update'=>array(
				'class'=>'application.components.actions.SimpleEdit',
				'pageTitle'=>'Редактирование меню',	
			),
			'create'=>array(
				'class'=>'application.components.actions.SimpleCreate',
				'pageTitle'=>'Добавление меню',		
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
			'ajaxOnly-admin',
			array('jQueryAjaxFilter')
		);
	}
	
	public function behaviors()
	{
		return array(
			'breadCrumbs'=>'BreadCrumbsBehavior',
			'ar'=>'AjaxRender'
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
		$this->breadCrumbs=array('Управление сайтом'=>array('/users/default/siteManagment'));

		$criteria=new CDbCriteria;

		$pages=new CPagination(Menu::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('Menu');
		$sort->defaultOrder='ident';
		$sort->applyOrder($criteria);

		$models=Menu::model()->findAll($criteria);

		$this->widget('FormWindow');
		
		$this->renderAjax('admin',array(
			'models'=>$models,
			'pages'=>$pages,
			'sort'=>$sort,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadModel($ident=null,$new=false)
	{
		if($this->_model===null)
		{
			if($new)
				$this->_model=new Menu();
			if($ident!==null || isset($_GET['ident']))
				$this->_model=Menu::model()->findbyPk($ident!==null ? $ident : $_GET['ident']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}

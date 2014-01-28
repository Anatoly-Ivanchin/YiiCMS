<?php

class DefaultController extends CController
{
	const PAGE_SIZE=10;
	
	public $layout='application.views.layouts.admin';

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='admin';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	public function actions()
	{
		return array(
			'delete'=>array(
				'class'=>'application.components.actions.SimpleDelete',
				'pageTitle'=>'Удаление страницы',
				'successMsg'=>'Страница удалена',
				'deleteMsg'=>'Вы действительно хотите удалить страницу \"{$model->title}\"?',
		
			),
			'update'=>array(
				'class'=>'application.components.actions.SimpleEdit',
				'pageTitle'=>'Редактирование страницы',	
			),
			'create'=>array(
				'class'=>'application.components.actions.SimpleCreate',
				'pageTitle'=>'Добавление страницы',
			),
			'upload'=>array(
				'class'=>'application.components.actions.AjaxUploadAction'
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
		$this->breadCrumbs=array(
			'Управление сайтом'=>array('/users/default/siteManagment')
		);

		$criteria=new CDbCriteria;

		$pages=new CPagination(BannerPage::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('BannerPage');
		$sort->applyOrder($criteria);

		$models=BannerPage::model()->findAll($criteria);
		
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
	public function loadModel($id=null,$new=false)
	{
		if($this->_model===null)
		{
			if($new)
				$this->_model=new BannerPage();
			if($id!==null || isset($_GET['id']))
				$this->_model=BannerPage::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
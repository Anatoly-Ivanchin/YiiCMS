<?php

class ScheduleController extends CController
{
	
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
				'pageTitle'=>'Удаление элемента',
				'successMsg'=>'Элемент удален',
				'deleteMsg'=>'Вы действительно хотите удалить элемент ?',
		
			),
			'update'=>array(
				'class'=>'application.components.actions.SimpleEdit',
				'pageTitle'=>'Редактирование элемента',	
			),
			'create'=>array(
				'class'=>'application.components.actions.SimpleCreate',
				'pageTitle'=>'Добавление элемента',
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
		$this->breadCrumbs=array(
			'Управление сайтом'=>array('/users/default/siteManagment')
		);

		$criteria=new CDbCriteria;

		$pages=new CPagination(LessonSchedule::model()->count($criteria));
		$pages->pageSize=Yii::app()->params['pageSizes']['admin'];
		$pages->applyLimit($criteria);

		$sort=new CSort('LessonSchedule');
		$sort->defaultOrder='eventDate DESC';
		$sort->applyOrder($criteria);

		$models=LessonSchedule::model()->findAll($criteria);
		
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
				$this->_model=new LessonSchedule();
			if($id!==null || isset($_GET['id']))
				$this->_model=LessonSchedule::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
<?php

class EnrollController extends CController
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
				'pageTitle'=>'Удаление заявки',
				'successMsg'=>'Заявка удалена',
				'deleteMsg'=>'Вы действительно хотите удалить заявку ?',
			),
			'create'=>array(
				'class'=>'application.components.actions.SimpleCreate',
				'pageTitle'=>'Посетить открытый урок',
			),
			'captcha'=>array('class'=>'CCaptchaAction'),
		);
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
			'ajaxOnly-admin,show,captcha',
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
				'actions'=>array('create','captcha','schedule'),	
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
		$this->breadCrumbs=array(
			'Управление сайтом'=>array('/users/default/siteManagment')
		);

		$criteria=new CDbCriteria;
		$criteria->with=array('s'=>'schedule');
		
		if(isset($_GET['sid']) && $lesson=LessonSchedule::model()->findByPk($_GET['sid']))
		$criteria->addColumnCondition(array('schedule.id'=>$_GET['sid']));

		$pages=new CPagination(Lesson::model()->count($criteria));
		$pages->pageSize=50;
		$pages->applyLimit($criteria);

		$sort=new CSort('Lesson');
		$sort->defaultOrder='createTime DESC';
		$sort->applyOrder($criteria);

		$models=Lesson::model()->findAll($criteria);
		
		$this->widget('FormWindow');

		$this->renderAjax('admin',array(
			'data'=>$models,
			'lesson'=>$lesson,
			'pages'=>$pages,
			'sort'=>$sort,
		));
	}
	
	public function actionShow()
	{
		$model=$this->loadModel();
		$this->pageTitle=$model->name.' '.$model->lastName.' '.$model->middleName;
		$this->breadCrumbs=array(
			'Управление сайтом'=>array('/users/default/siteManagment'),
			'Просмотр заявок'=>array('admin'),
		);
		$this->render('show',array('model'=>$model));
	}
	
	public function actionSchedule()
	{
		if(!isset($_POST['lid']))
			return;
		$cri=new CDbCriteria();
		$cri->addColumnCondition(array('programmId'=>$_POST['lid']));
		$cri->addCondition('eventDate>NOW()');
		$cri->with=array('a'=>'enrolls');
		$cri->group="t.id, t.eventDate";
		$cri->having="COUNT(enrolls.id)<t.vacancies";
		$cri->order='t.eventDate';
		
		$data=LessonSchedule::model()->findAll($cri);
		$this->renderPartial('schedule',array('data'=>$data));
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
				$this->_model=new Lesson();
			if($id!==null || isset($_GET['id']))
				$this->_model=Lesson::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
<?php

class BlockController extends CController
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
			'captcha'=>array('class'=>'CCaptchaAction'),
			'delete'=>array(
				'class'=>'application.components.actions.SimpleDelete',
				'pageTitle'=>'Удаление раздела',
				'successMsg'=>'Раздел удален',
				'deleteMsg'=>'Вы действительно хотите удалить раздел \"{$model->title}\"?',
		
			),
			'update'=>array(
				'class'=>'application.components.actions.SimpleEdit',
				'pageTitle'=>'Редактирование раздела',	
			),
			'create'=>array(
				'class'=>'application.components.actions.SimpleCreate',
				'pageTitle'=>'Добавление раздела',		
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
			'ajaxOnly-show,admin,captcha',
			array('jQueryAjaxFilter')
		);
	}
	
	public function behaviors()
	{
		return array(
			'breadCrumbs'=>'BreadCrumbsBehavior',
			'metaData'=>'application.components.MetaBehaviors.ControllerMetaBehavior',
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
			array('allow',  // allow all users to perform 'list' and 'show' actions
				'actions'=>array('show','captcha'),
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
	 * Shows a particular model.
	 */
	public function actionShow()
	{
		$this->layout=null;
		$block = $this->loadModel($_GET['url']);
		$newQ=$this->newQuestion($block);
		$this->registerMeta($block->getMeta(),$block->title);
		
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(
			array(
				'publish'=>true,
				'blockId'=>$block->id,
			)
		);
		$criteria->order='qTime DESC';
		
		$withOption=array('author');
		
		$count = FaqItems::model()->with($withOption)->count($criteria);

		$pages=new CPagination($count);
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$models=FaqItems::model()->with($withOption)->findAll($criteria);
		
		if(Yii::app()->user->checkAccess('admin'))
			$this->widget('FormWindow');
		
		$this->renderAjax('show',
			array(
				'block'=>$block,
				'questions'=>$models,
				'newQ'=>$newQ,
				'pages'=>$pages,
			)
		);
	}
	
	protected function newQuestion($block)
	{
		$q=$this->CreateQeustion($block);
		
		if(isset($_POST['FaqItems']))
		{
			$q->attributes=$_POST['FaqItems'];
			if($q->save()){
				$q=$this->CreateQeustion($block);
				$q->answer='new';
			}
		}
		return $q;
	}
	
	protected function CreateQeustion($block)
	{
		$q=new FaqItems();
		$q->blockId=$block->id;
		$q->setScenario(FaqItems::SCENARIO_QUESTION);
		$q->publish=(boolean)$block->autoPublish;
		return $q;
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->breadCrumbs=array('Управление сайтом'=>array('/users/default/siteManagment'));

		$criteria=new CDbCriteria;

		$pages=new CPagination(FaqBlock::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('FaqBlock');
		$sort->defaultOrder='url';
		$sort->applyOrder($criteria);

		$models=FaqBlock::model()->findAll($criteria);

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
	public function loadModel($url=null,$new=false)
	{
		if($this->_model===null)
		{
			if($new)
				$this->_model=new FaqBlock();
			if(isset($_GET['id']))
				$this->_model=FaqBlock::model()->findbyPk($_GET['id']);
			else if($url!=null)
				$this->_model=FaqBlock::model()->findByAttributes(array('url'=>$url));
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}

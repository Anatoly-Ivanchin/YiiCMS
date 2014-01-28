<?php

class ItemController extends CController
{
	const PAGE_SIZE=10;
	
	public $layout='application.views.layouts.admin';

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;
	
	public $withoutAnswers=false; 
	
	/**
	 * @var FaqBlock
	 */
	private $_faqBlock;

	public function actions()
	{
		return array(
			'delete'=>array(
				'class'=>'application.components.actions.SimpleDelete',
				'pageTitle'=>'Удаление сообщения',
				'successMsg'=>'Сообщение удалено',
				'deleteMsg'=>'Вы действительно хотите удалить сообщение \"{$model->title}\"?',
		
			),
			'update'=>array(
				'class'=>'application.components.actions.SimpleEdit',
				'pageTitle'=>'Редактировать сообщение',
				'onLoad'=>'setAnswerScenarion'	
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
			'ajaxOnly-show,admin',
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
			array('allow',  
				'actions'=>array('show'),
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
		$post = $this->loadModel();
		$this->registerMeta($post->getMeta(),$post->title);
		
		if(Yii::app()->user->checkAccess('admin'))
			$this->widget('FormWindow');
		
		$this->renderAjax('show',array('model'=>$post));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->breadCrumbs=array(
			'Управление сайтом'=>array('/users/default/siteManagment'),
			'Управление разделами'=>array('block/admin')
		);
		
		$block = $this->loadBlock();
		$this->withoutAnswers=isset($_GET['withoutAnswers'])?$_GET['withoutAnswers']=='true':false;
		
		$this->processAdminCommand();

		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array('blockId'=>$block->id));
		if($this->withoutAnswers)
			$criteria->addCondition('answer IS NULL');

		$pages=new CPagination(FaqItems::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('FaqItems');
		$sort->defaultOrder='qTime DESC, publish ASC';
		$sort->applyOrder($criteria);

		$models=FaqItems::model()->findAll($criteria);
		
		$this->widget('FormWindow');

		$this->renderAjax('admin',array(
			'models'=>$models,
		    'block'=>$block,
			'pages'=>$pages,
			'sort'=>$sort,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadModel($id=null)
	{
		if($this->_model===null)
		{
			if($id!==null || isset($_GET['id']))
				$this->_model=FaqItems::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null || !Yii::app()->user->checkAccess('admin') && !$this->_model->publish)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
	
	protected function loadBlock()
	{
		if($this->_faqBlock===null)
		{
			if(isset($_GET['block']))
				$this->_faqBlock=FaqBlock::model()->findbyPk($_GET['block']);
			if($this->_faqBlock===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_faqBlock;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param $model FaqItems
	 */
	public function setAnswerScenarion($data)
	{
		$model=$data['model'];
		$model->setScenario(FaqItems::SCENARIO_ANSWER);
		$model->sendMail=$model->block->notifyUser && !$model->hasAnswer();
		return array('model'=>$model);
	}

	/**
	 * Executes any command triggered on the admin page.
	 */
	protected function processAdminCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='switchpublish')
		{
			$item=$this->loadModel($_POST['id']);
			$publish = $item->publish;
			$item->publish=!$publish;
			$item->save();
		}
	}
}

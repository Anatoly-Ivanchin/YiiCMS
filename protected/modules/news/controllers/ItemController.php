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
	
	/**
	 * @var NewsBlock
	 */
	private $_newsBlock;

	public function actions()
	{
		return array(
			'delete'=>array(
				'class'=>'application.components.actions.SimpleDelete',
				'pageTitle'=>'Удаление новости',
				'successMsg'=>'Новость удалена',
				'deleteMsg'=>'Вы действительно хотите удалить новость \"{$model->title}\"?',
		
			),
			'update'=>array(
				'class'=>'application.components.actions.SimpleEdit',
				'pageTitle'=>'Редактирование новости',	
			),
			'create'=>array(
				'class'=>'application.components.actions.SimpleCreate',
				'pageTitle'=>'Добавление новости',
				'onLoad'=>'beforeCreate',		
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
			'ajaxOnly-show,admin,upload',
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
				'actions'=>array('list','show','upload'),
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
			'Управление новостными блоками'=>array('block/admin')
		);
		
		$block = $this->loadBlock();
		
		$this->processAdminCommand();

		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array('newsBlockId'=>$block->url));

		$pages=new CPagination(News::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('News');
		$sort->defaultOrder='createTime DESC, published ASC';
		$sort->applyOrder($criteria);

		$models=News::model()->findAll($criteria);
		
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
	public function loadModel($id=null,$new=false)
	{
		if($this->_model===null)
		{
			if($new)
				$this->_model=new News();
			if($id!==null || isset($_GET['id']))
				$this->_model=News::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null || !Yii::app()->user->checkAccess('admin') && !$this->_model->published)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
	
	protected function loadBlock()
	{
		if($this->_newsBlock===null)
		{
			if(isset($_GET['block']))
				$this->_newsBlock=NewsBlock::model()->findbyPk($_GET['block']);
			if($this->_newsBlock===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_newsBlock;
	}
	
	public function beforeCreate($model)
	{
		$model->newsblock=$this->loadBlock();
		$model->newsBlockId=$this->loadBlock()->url;
		return $model;
	}

	/**
	 * Executes any command triggered on the admin page.
	 */
	protected function processAdminCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='switchpublish')
		{
			$item=$this->loadModel($_POST['id']);
			$publish = $item->published;
			$item->published=!$publish;
			$item->save();
		}
	}
}

<?php

class CategoriesController extends CController
{
	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='admin';
	
	public $layout='application.views.layouts.admin';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_category;
	private $metaTags;
	
	//public $layout='main';

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
	
	public function actions()
	{
		return array(
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
			'upload'=>array(
				'class'=>'application.components.actions.UploadAction'
			),
		);
	}
	
	public function behaviors()
	{
		return array(
			'breadCrumbs'=>'BreadCrumbsBehavior',
			'metaData'=>'application.components.MetaBehaviors.ControllerMetaBehavior',
			'order'=>array(
		        'class'=>'application.components.behaviors.OrderBehavior',
		        'modelClass'=>'Categories',
		        'modelListCondition'=>'parentId={$this->getModel()->parent?$this->getModel()->parent->id:"NULL"}',
		    ),
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
				'actions'=>array('upload'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated users to perform any action
				'roles'=>array('admin'),
			),			
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	
	/**
	 * Manages all posts.
	 */
	public function actionAdmin()
	{
		$this->breadCrumbs=array('Управление сайтом'=>array('/users/default/siteManagment'));
		$this->updatePriority();
		
		$criteria=new CDbCriteria;

		$pages=new CPagination(Categories::model()->count());
		$pages->applyLimit($criteria);

		$sort=new CSort('Categories');
		$sort->defaultOrder='parentId, priority';
		$sort->applyOrder($criteria);

		$cats=Categories::model()->findAll($criteria);
		
		$this->widget('FormWindow');

		$this->renderAjax('admin',array(
			'pages'=>$pages,
			'cats'=>$cats,
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
		if($this->_category===null)
		{
			if($new)
				$this->_category=new Categories();
			if($id!==null || isset($_GET['id']))
			{
				$this->_category=Categories::model()->findByPk($id!==null ? $id : $_GET['id']);
			}
			if($this->_category===null)
			{
				throw new CHttpException(404,'The requested page does not exist.');
			}
		}
		return $this->_category;
	}
	
	protected function getPictureTypes()
	{
		return Categories::model()->pictures();
	}
	
}
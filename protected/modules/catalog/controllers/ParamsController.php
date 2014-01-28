<?php

class ParamsController extends CController
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
	private $_param;
	
	//public $layout='main';

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
	
	public function actions()
	{
		return array(
			'delete'=>array(
				'class'=>'application.components.actions.SimpleDelete',
				'pageTitle'=>'Удаление параметра',
				'successMsg'=>'Параметр удален',
				'deleteMsg'=>'Вы действительно хотите удалить параметр \"{$model->name}\"?',
		
			),
			'update'=>array(
				'class'=>'application.components.actions.SimpleEdit',
				'pageTitle'=>'Редактирование параметра',	
			),
			'create'=>array(
				'class'=>'application.components.actions.SimpleCreate',
				'pageTitle'=>'Добавление параметра',
				'onLoad'=>'beforeCreate',		
			),
		);
	}
	
	public function behaviors()
	{
		return array(
			'breadCrumbs'=>'BreadCrumbsBehavior',
			'order'=>array(
		        'class'=>'application.components.behaviors.OrderBehavior',
		        'modelClass'=>'Params',
		        'modelListCondition'=>'categoryId={$this->getModel()->category->id}',
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
			array('allow', // allow authenticated users to perform any action
				'roles'=>array('admin'),
			),			
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionAdmin()
	{
		$cat=$this->loadCategory();
		
		$this->breadCrumbs=array(
			'Управление сайтом'=>array('/users/default/siteManagment'),
			'Управление разделами каталога'=>array('categories/admin')
		);
		$this->updatePriority();
		
		$criteria=new CDbCriteria;
		$criteria->condition='categoryId='.$cat->id;

		$pages=new CPagination(Params::model()->count($criteria));
		$pages->applyLimit($criteria);

		$sort=new CSort('Params');
		$sort->defaultOrder='priority,updateDate';
		$sort->applyOrder($criteria);
		
		$params = Params::model()->findAll($criteria);
		
		$this->widget('FormWindow');
		
		$this->renderAjax('admin',array(
			'pages'=>$pages,
			'params'=>$params,
		    'cat'=>$cat,
			'sort'=>$sort,
		));
	}

	public function beforeCreate($data)
	{
		$data[model]->categoryId=$this->loadCategory()->id;
		return $data;
	}

	public function loadModel($id=null,$new=false)
	{
		if($this->_param===null)
		{
			if($new)
				$this->_param=new Params();
			if($id!==null || isset($_GET['id']))
			{
				$this->_param=Params::model()->findByPk($id!==null ? $id : $_GET['id']);
			}
			if($this->_param===null)
			{
				throw new CHttpException(404,'The requested page does not exist.');
			}
		}
		return $this->_param;
	}

	protected function loadCategory($id=null)
	{
		if($this->_category===null)
		{
			if($id!==null || isset($_GET['cat']))
			{
				$this->_category=Categories::model()->findByPk($id!==null ? $id : $_GET['cat']);
			}
			if($this->_category===null)
			{
				throw new CHttpException(404,'The requested page does not exist.');
			}
		}
		return $this->_category;
	}
	
}
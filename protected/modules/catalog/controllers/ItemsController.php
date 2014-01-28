<?php

class ItemsController extends CController
{
	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='admin';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_item;
	private $_cat;
	
	public $layout='application.views.layouts.admin';

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
			'metaData'=>'application.components.MetaBehaviors.ControllerMetaBehavior',
			'order'=>array(
		        'class'=>'application.components.behaviors.OrderBehavior',
		        'modelClass'=>'Items',
		        'modelListCondition'=>'categoryId={$this->getModel()->category->id}',
		    ),
			'ar'=>'AjaxRender'
		);
	}

	public function actions()
	{
		return array(
		    'order'=>array(
		        'class'=>'OrderAction',
		        'modelClass'=>'Items',
		        'priorityField'=>'priority',
		        'modelListCondition'=>'categoryId={$this->getModel()->category->id}',
		        'renderContent'=>'generateAdminDataGrid',
		    ),
		    'delete'=>array(
				'class'=>'application.components.actions.SimpleDelete',
				'pageTitle'=>'Удаление элемента каталога',
				'successMsg'=>'Элемент удален',
				'deleteMsg'=>'Вы действительно хотите удалить элемент каталога \"{$model->title}\"?',
			),
			'update'=>array(
				'class'=>'application.components.actions.SimpleEdit',
				'pageTitle'=>'Редактирование элемента каталога',	
			),
			'create'=>array(
				'class'=>'application.components.actions.SimpleCreate',
				'pageTitle'=>'Добавление элемента каталога',
				'onLoad'=>'setDefaultCat',		
			),
			'upload'=>array(
				'class'=>'application.components.actions.AjaxUploadAction',
				'single'=>false,
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
			array('allow', // allow authenticated users to perform any action
				'roles'=>array('admin'),
			),
			array('allow',
				'actions'=>array('upload'),
				'users'=>array('*'),
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
		$cat = $this->loadCat();
		
		$this->breadCrumbs=array(
			'Управление сайтом'=>array('/users/default/siteManagment'),
			'Управление разделами каталога'=>array('categories/admin')
		);
		$this->updatePriority();
		
		$criteria=new CDbCriteria;
		if($cat)
		    $criteria->addCondition('categoryId = '.$cat->id);

		$pages=new CPagination(Items::model()->count($criteria));
		$pages->applyLimit($criteria);

		$sort=new CSort('Items');
		$sort->defaultOrder='categoryId,priority';
		$sort->applyOrder($criteria);

		$items=Items::model()->findAll($criteria);
		$defaultCat=Categories::model()->find('isnull(parentId)');
		
		$this->widget('FormWindow');

		$this->renderAjax('admin',array(
			'pages'=>$pages,
			'items'=>$items,
			'sort'=>$sort,
		    'defaultCat'=>$defaultCat,
		    'cat'=>$cat,
		));
	}
	
	public function actionParams()
	{
		if(Yii::app()->getRequest()->getIsAjaxRequest())
		{
			if(isset($_POST['item']))
			    $item = $this->loadModel($_POST['item']);
			else
			    $item = new Items();
			$criteria = new CDbCriteria();
			$criteria->condition =isset($_POST['param'])?'id='.$_POST['param']:'isnull(parentId)';
			$cat = Categories::model()->find($criteria);
			if($cat == null)
			    throw new CHttpException(404,'The requested page does not exist.');
			$this->renderPartial('_aParams',array('category'=>$cat,'item'=>$item));
		}
		else
		    throw new CHttpException(404,'The requested page does not exist.');
	}
	
	protected function newParam($cat)
	{
		$param=new Params();
		if(isset($_POST['Params']))
		{
			$param->categoryId=$cat->id;
			$param->attributes=$_POST['Params'];
			    if($param->save())
				    $param = new Params();
		}
		return $param;
	}
	
	protected function loadParam()
	{
		if (isset($_GET['param']))
		{
			$param = Params::model()->findByPk($_GET['param']);
		}
		if($param===null)
		{
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $param;
	}
	
	public function actionUpdateParam()
	{
		$param = $this->loadParam();
	    if(isset($_POST['Params']))
		{
			$param->attributes=$_POST['Params'];
			if ($param->save())
			    $this->redirect(array('params','id'=>$param->categoryId));
		}
		$this->render('params/update',array('param'=>$param));
	}
	
	public function actionDeleteParam()
	{
		$param = $this->loadParam();
		if(isset($_POST['submitParam']) ||isset($_POST['cancelParam']))
		{
			if(isset($_POST['submitParam']))
			    $param->delete();
			$this->redirect(array('params','id'=>$param->categoryId));
		}
		$this->render('params/delete',array('param'=>$param));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadModel($id=null,$new=false)
	{
		if($this->_item===null)
		{
			if($new)
				$this->_item=new Items();
			if($id!==null || isset($_GET['id']))
			{
				$this->_item=Items::model()->findByPk($id!==null ? $id : $_GET['id']);
			}
			if($this->_item===null)
			{
				throw new CHttpException(404,'The requested page does not exist.');
			}
		}
		return $this->_item;
	}
	
	protected function loadCat()
	{
		if($this->_cat===null)
		{
			if(isset($_GET['cid']))
			{
				$this->_cat=Categories::model()->findByPk($_GET['cid']);
				if($this->_cat===null)
				    throw new CHttpException(404,'The requested page does not exist.');
			}
			else return false;
			
		}
		return $this->_cat;
	}
	
	public function getCategories($parent=null)
	{
		$criteria = new CDbCriteria();
		$criteria->order='parentId';
		$cats = Categories::model()->findAll($criteria);
		$result = array();
		foreach ($cats as $cat)
		   $result[$cat->id]=$cat->title;
		return $result;
	}
	
	protected function getPictureTypes()
	{
		return Items::model()->pictures();
	}
	
	public function setDefaultCat($model)
	{
		$model->categoryId=$this->loadCat();
		return $model;
	}
}
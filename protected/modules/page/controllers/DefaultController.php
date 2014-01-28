<?php

class DefaultController extends CController
{
	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='admin';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_page;
	
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
			array('allow',  // allow all users to perform 'list' and 'show' actions
			    'actions'=>array('show'),
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
	 * Shows a particular post.
	 */
	public function actionShow()
	{
		$page=$this->loadModel();
		
		$this->registerMeta($page->getMeta(),$page->title);
		
		$this->widget('FormWindow');
			
		$view='show';
		if($page->url==null && $this->getViewFile('main_page'))
			$view='main_page';

		$this->renderAjax($view,array('page'=>$page));
	}
	
	
	/**
	 * Manages all posts.
	 */
	public function actionAdmin()
	{
		$this->layout='application.views.layouts.admin';
		$this->breadCrumbs=array('Управление сайтом'=>array('/users/default/siteManagment'));
		
		$criteria=new CDbCriteria;

		$pages=new CPagination(Page::model()->count());
		$pages->applyLimit($criteria);

		$sort=new CSort('Page');
		$sort->defaultOrder='url';
		$sort->applyOrder($criteria);

		$contentPages=Page::model()->findAll($criteria);
		
		$this->widget('FormWindow');

		$this->renderAjax('admin',array(
				'pages'=>$pages,
				'contentPages'=>$contentPages,
				'sort'=>$sort,
		));
	}

	public function loadModel($url=null,$new=false)
	{
		if($this->_page===null)
		{
			if($new)
				return $this->_page=new Page();
			$url=($url!==null) ? $url : ($_GET['url']==null)?"":$_GET['url'];
			$this->_page=Page::model()->findByAttributes(array('url'=>$url));
			if($this->_page===null)
			{
				throw new CHttpException(404,'The requested post does not exist.');
			}
		}
		return $this->_page;
	}
	
}
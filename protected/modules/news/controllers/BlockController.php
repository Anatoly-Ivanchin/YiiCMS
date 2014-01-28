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
			'delete'=>array(
				'class'=>'application.components.actions.SimpleDelete',
				'pageTitle'=>'Удаление новостного блока',
				'successMsg'=>'Блок удален',
				'deleteMsg'=>'Вы действительно хотите удалить блок \"{$model->title}\"?',
		
			),
			'update'=>array(
				'class'=>'application.components.actions.SimpleEdit',
				'pageTitle'=>'Редактирование новостного блока',	
			),
			'create'=>array(
				'class'=>'application.components.actions.SimpleCreate',
				'pageTitle'=>'Добавление новостного блока',		
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
				'actions'=>array('list','show'),
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
		$block = $this->loadModel();
		$this->registerMeta($block->getMeta(),$block->title);
		
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(
			array(
				'published'=>true,
				'newsBlockId'=>$block->url,
			)
		);
		$criteria->order='createTime DESC';
		
		$withOption=array('author');
		
		$tag=null;
		if(isset($_GET['tag']))
		{
			$tag=Tag::model()->findByPk($_GET['tag']);
			if($tag)
				$withOption['tagFilter']=array('params'=>array(':tag'=>$tag->id));
		}
		
		$count = News::model()->with($withOption)->count($criteria);

		$pages=new CPagination($count);
		$type=$block->isGallery?'Gallery':'News';
		$pages->pageSize=Yii::app()->params['pageSizes'][$type];
		$pages->applyLimit($criteria);

		$models=News::model()->with($withOption)->findAll($criteria);
		
		if(Yii::app()->user->checkAccess('admin'))
			$this->widget('FormWindow');
			
		if($this->getModule()->galleryMode && $block->isGallery)
			$view='gallery';
		else
			$view='show';
		
		$this->renderAjax($view,
			array(
				'block'=>$block,
				'tag'=>$tag,
				'news'=>$models,
				'pages'=>$pages,
			)
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->breadCrumbs=array('Управление сайтом'=>array('/users/default/siteManagment'));

		$criteria=new CDbCriteria;

		$pages=new CPagination(NewsBlock::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('NewsBlock');
		$sort->defaultOrder='url';
		$sort->applyOrder($criteria);

		$models=NewsBlock::model()->findAll($criteria);

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
				$this->_model=new NewsBlock();
			if($url!==null || isset($_GET['url']))
				$this->_model=NewsBlock::model()->findbyPk($url!==null ? $url : $_GET['url']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}

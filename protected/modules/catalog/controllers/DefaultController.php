<?php

class DefaultController extends CController
{
 
	public $defaultAction='ItemsList';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_item;
	private $_category;
	
	//public $layout='main';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
			array('jQueryAjaxFilter')
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
				'roles'=>array('*'),
			),
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
	
	public function actionItemsList()
	{
		$cat = $this->loadCategory();
		$this->registerMeta($cat->getMeta(),$cat->title);
				
		
		// items
		$subCatIds=array();
		foreach ($cat->getSubCategories(true) as $c)
		{
			$subCatIds[]=$c->id;
		}
		$itemCriteria=new CDbCriteria;
		$itemCriteria->addInCondition('categoryId',$subCatIds);

		$itemPages=new CPagination(Items::model()->count($itemCriteria));
		$itemPages->pageVar='ipage';
		$itemPages->setPageSize(Yii::app()->params['pageSizes']['Catalog']['Items']);
		$itemPages->applyLimit($itemCriteria);

		$sort=new CSort('Items');
		$sort->defaultOrder='priority';
		$sort->applyOrder($itemCriteria);

		$items=Items::model()->findAll($itemCriteria);
		
		$this->widget('FormWindow');
		
		$this->renderAjax('list',array(
		    'model'=>$cat,
			'items'=>array(
				'models'=>$items,
				'pages'=>$itemPages,
				'sort'=>$sort,
			)));
	}
	
	public function actionSearch()
	{
		$this->regScripts();
		
		$criteria=new CDbCriteria;
		$patterns=$this->getSearchPatterns();
		foreach($patterns as $p)
		{
			$criteria->addCondition('title LIKE "%'.$p.'%"','OR');
			$criteria->addCondition('shortDescription LIKE "%'.$p.'%"','OR');
			$criteria->addCondition('description LIKE "%'.$p.'%"','OR');
		}

		$pages=new CPagination(Items::model()->count($criteria));
		$pages->setPageSize(self::PAGE_SIZE);
		$pages->applyLimit($criteria);

		$sort=new CSort('Items');
		$sort->applyOrder($criteria);

		$items=Items::model()->findAll($criteria);
		
		$this->render('search',array(
		    'items'=>$items,
		    'sort'=>$sort,
		    'pages'=>$pages,
		    'patterns'=>$patterns)
		);
	}
	
	protected function getSearchPatterns()
	{
		$searchStr=Yii::app()->getRequest()->getParam('searchpattern');
		if($searchStr==null || strlen($searchStr)==0)
		    $this->redirect($this->loadCategory()->getFullUrl());
		return preg_split('/\s/', $searchStr);
	}
	
    public function actionItem()
	{
		$item=$this->loadItem();
		$this->registerMeta($item->getMeta(),$item->title);
		
		$this->widget('FormWindow');
		
		$this->renderAjax('item',array('item'=>$item));
	}
	
	protected function loadCategory()
	{
		if($this->_category===null)
		{
			if(isset($_GET['cat']))
			{
				$this->_category=Categories::model()->findByAttributes(array('url'=>$_GET['cat']));
			}
			else
			    $this->_category=Categories::model()->find('isnull(parentId)');
			if($this->_category===null)
			{
				throw new CHttpException(404,'The requested page does not exist.');
			}
		}
		return $this->_category;
	}

	protected function loadItem()
	{
		if($this->_item===null)
		{
			if(isset($_GET['item']))
			{
				$this->_item=Items::model()->findByAttributes(array('url'=>$_GET['item']));
			}
			if($this->_item===null)
			{
				throw new CHttpException(404,'The requested page does not exist.');
			}
		}
		return $this->_item;
	}
}
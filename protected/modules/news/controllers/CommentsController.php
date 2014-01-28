<?php

class CommentsController extends CController
{
	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='show';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;
	
	/**
	 * @var NewsBlock
	 */
	private $_news;

	public function actions()
	{
		return array('captcha'=>array('class'=>'CCaptchaAction'),);
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
			'ajaxOnly-captcha',
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
			array('allow',  
				'actions'=>array('show','captcha'),
				'users'=>array('*'),
			),
			array('allow',  
				'actions'=>array('edit','delete'),
				'users'=>array('@'),
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
		$post = $this->loadNews();
		
		$new=$this->newComment($post->id);
		
		$cri=new CDbCriteria();
		$cri->addColumnCondition(array('newsId'=>$post->id))
		->order='updateTime ASC';
		$comments=Comment::model()->findAll($cri);
		
		$this->renderPartial('show',array('comments'=>$comments,'newcomment'=>$new),false,true);
	}
 
	protected function newComment($news)
	{
		$comment=new Comment();
		$comment->newsId=$news;
		
		if(isset($_POST['Comment']))
		{
			$comment->attributes=$_POST['Comment'];
			if($comment->save())
				$comment=new Comment();
				$comment->newsId=$news;
		}
		return $comment;
	}
	
	public function actionEdit()
	{
		$comment=$this->loadModel();
		if(isset($_POST['cancel']))
		{
			$this->renderPartial('_item',array('comment'=>$comment),false,true);
			return;
		}
		if(isset($_POST['Comment']))
		{
			$comment->attributes=$_POST['Comment'];
			if($comment->save())
			{
				$comment->refresh();
				$this->renderPartial('_item',array('comment'=>$comment),false,true);
				return;
			}
		}
		$this->renderPartial('edit',array('comment'=>$comment),false,true);
	}
	
	public function actionDelete()
	{
		$comment=$this->loadModel();
		if(isset($_POST['cancel']))
		{
			$this->renderPartial('_item',array('comment'=>$comment),false,true);
			return;
		}
		if(isset($_POST['delete']))
		{
			if($comment->delete())
			{
				$this->renderPartial('_deleted',array('comment'=>$comment),false,true);
				return;
			}
		}
		$this->renderPartial('delete',array('comment'=>$comment),false,true);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{

			if(isset($_POST['id']))
				$this->_model=Comment::model()->findbyPk($_POST['id']);
			if($this->_model===null || !Yii::app()->user->checkAccess('admin') && ($this->_model->userId!=Yii::app()->user->id || $this->_model->userId==null))
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
	
	protected function loadNews()
	{
		if($this->_news===null)
		{
			if(isset($_POST['news']))
				$this->_news=News::model()->findbyPk($_POST['news']);
			if($this->_news===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_news;
	}
}

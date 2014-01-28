<?php

class QuestionController extends CController
{
	const PAGE_SIZE=10;

    public $cachePrefix = 'question_';   
	
	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
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
		        'actions'=>array('vote'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel();
		$model=$this->updateRecors($model);
		if (is_bool($model) && $model)
		    $this->redirect(array('admin'));
		
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelete()
	{
		$model = $this->loadModel();
		if(isset($_POST['submit']))
            $model->delete();
        if(isset($_POST['submit']) || isset($_POST['cancel']))
            $this->redirect(array('admin'));
            
        $this->render('delete',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$newModel=$this->createNewModel();
		
		$this->processAdminCommand();
		
		$criteria=new CDbCriteria;

		$pages=new CPagination(Question::model()->count());
		$pages->applyLimit($criteria);

		$sort=new CSort('Question');
		$sort->defaultOrder='updateTime DESC';
		$sort->applyOrder($criteria);

		$models=Question::model()->findAll($criteria);

		$this->render('admin',array(
			'models'=>$models,
			'newModel'=>$newModel,
			'pages'=>$pages,
			'sort'=>$sort,
		));
	}
	
	public function actionVote()
	{
		//if(Yii::app()->getRequest()->getIsAjaxRequest())
		$question = Question::model()->findByAttributes(array('active'=>true));

		if (isset($_POST['question_form_submitted']) && isset($_POST['answer']))
		{
			$vote = new Vote();
			$vote->answerId = $_POST['answer'];
			$vote->save();
		}
			
		if($question->isVoted())
			$this->renderPartial('result',array('q'=>$question));
		else
		{
			$answers=array();
			foreach ($question->answers as $a)
			    $answers[$a->id]=$a->text;
			
			$this->renderPartial('voteform',array('q'=>$question,'answers'=>$answers));
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	protected  function loadModel($id=null)
	{
		if($this->_model===null)
		{
			$modelId=($id!=null)?$id:$_GET['id'];
			if($modelId!=null)
				$this->_model=Question::model()->findbyPk($modelId);
			if($this->_model===null)
				throw new CHttpException(404,'Запрашиваемая  страница не существует.');
		}
		return $this->_model;
	}
	
	protected function processAdminCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='activate')
		{
			$item=$this->loadModel($_POST['id']);
			$isActive = $item->active;
			$item->active=!$isActive;
			$item->save();
		}
	}
	
	protected function createNewModel()
	{
		$model=new Question();
		$model=$this->updateRecors($model);
		if(is_bool($model) && $model)
		    $model=new Question();
		return $model;
	}
	
	protected function updateRecors($model)
	{
	    if(isset($_POST['Question']))
		{
			$model->attributes=$_POST['Question'];
			if(isset($_POST['preview']))
			{
			    $model->validate();
			    return $model;
			}
			else if($model->save())
				return true;
		}
		return $model;
	}
}

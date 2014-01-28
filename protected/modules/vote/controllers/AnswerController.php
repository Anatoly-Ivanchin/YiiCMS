<?php

class AnswerController extends CController
{
	const PAGE_SIZE=10;

    public $cachePrefix = 'answer_';   
	
	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model=null;
	private $_question=null;

	/**
	 * @return array action filters
	 */
	
	public function actions()
	{
		return array(
			'order'=>array(
		        'class'=>'OrderAction',
		        'modelClass'=>'Answer',
		        'modelListCondition'=>'questionId={$this->getModel()->question->id}',
		        'renderContent'=>'generateAdminDataGrid',
		    ),
	    );
	}
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
		$qid = $model->question->id;
		$model=$this->updateRecors($model);
		
		if (is_bool($model) && $model)
		    $this->redirect(array('admin','qid'=>$qid));
		
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
            $this->redirect(array('admin','qid'=>$model->question->id));
            
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
		
		$question = $this->loadQuestion();
		
		$dataGrid = $this->generateAdminDataGrid(true);

		$this->render('admin',array(
			'models'=>$models,
			'question'=>$question,
			'newModel'=>$newModel,
			'dataGrid'=>$dataGrid,
		));
	}
	
	public function generateAdminDataGrid($return=false)
	{
		$question = $this->loadQuestion();
		
		$criteria=new CDbCriteria;
		$criteria->addColumnCondition(array('questionId'=>$question->id));

		$pages=new CPagination(Answer::model()->count($criteria));
		$pages->applyLimit($criteria);

		$sort=new CSort('Answer');
		$sort->defaultOrder='priority';
		$sort->applyOrder($criteria);

		$models=Answer::model()->findAll($criteria);
		
		$output = $this->renderPartial(
			'_adminDataGrid',
			array(
				'models'=>$models,
				'pages'=>$pages,
				'sort'=>$sort,
			),
			$return
		);
		if($return)
		    return $output;
	}
	
	public function actionVote()
	{
		//if(Yii::app()->getRequest()->getIsAjaxRequest())
		$question = Question::model()->findByAttributes(array('active'=>true));
		$answers= array();
		foreach ($question->answers as $answer)
		    $answers[$answer->id]=$answer->text;

		
		if($question->isVoted())
			$view='vote/result';
		else
		{
			$view='vote/form';
			
			if (isset($_POST['question_form_submitted']) && isset($_POST['answer']))
			{
				$vote = new Vote();
				$vote->question_id = $question->id;
				$vote->answer_id = $_POST['answer'];
				if ($vote->save())
				    $view='vote/result';
			}
		}
			
		$this->renderPartial($view,array('q'=>$question,'answers'=>$answers));
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
				$this->_model=Answer::model()->findbyPk($modelId);
			if($this->_model===null)
				throw new CHttpException(404,'Запрашиваемая  страница не существует.');
		}
		return $this->_model;
	}
	
	protected  function loadQuestion($id=null)
	{
		if($this->_question===null)
		{
			$modelId=($id!=null)?$id:$_GET['qid'];
			if($modelId!=null)
				$this->_question=Question::model()->findbyPk($modelId);
			if($this->_question===null)
				throw new CHttpException(404,'Запрашиваемая  страница не существует.');
		}
		return $this->_question;
	}
	
	protected function createNewModel()
	{
		$model=new Answer();
		$model->questionId=$this->loadQuestion()->id;
		$model=$this->updateRecors($model);
		if(is_bool($model) && $model)
		    $model=new Answer();
		return $model;
	}
	
	protected function updateRecors($model)
	{
	    if(isset($_POST['Answer']))
		{
			$model->attributes=$_POST['Answer'];
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

<?php

class SiteController extends CController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array();
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	        $this->render('error', $error);
	    else 	
	    throw new CHttpException(404,'Запрашиваемая страница не существует.');
	}
}
<?php

class QuestionView extends CWidget
{	
	private $activeQuestion=null;
	/**
	 * Enter description here...
	 *
	 * @return boolean
	 */
	public function getActiveQuestion()
	{
		if($this->activeQuestion==null)
		{
			$cri = new CDbCriteria();
			$cri->addColumnCondition(array('active'=>true));
			
			$this->activeQuestion=Question::model()->find($cri);
		}
		return $this->activeQuestion;					
	}

	public function run()
	{
		$question = $this->getActiveQuestion();
		if ($question==null)
		    return;
		    
		$this->render('container',array('q'=>$question));
	}
}
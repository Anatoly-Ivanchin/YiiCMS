<?php

class FaqItems extends CActiveRecord
{
	const SCENARIO_QUESTION = 'q';
	const SCENARIO_ANSWER='a';
	const SHORT_QUESTION=250;
	
	public $captcha;
	public $sendMail=false;
	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Faq_Item';
	}
	
	public function behaviors()
	{
		return array(
			'metaData'=>'application.components.MetaBehaviors.ModelMetaBehavior',
		);
	}
	
	public function rules()
	{
		$rules = array(
		    array('title', 'length', 'max'=>64,'min'=>2),
		    array('title','required'),
		    
		    array('question','required','on'=>self::SCENARIO_QUESTION),
		    array('question', 'length', 'min'=>10),
		    
		    array('answer','required','on'=>self::SCENARIO_ANSWER),
		    
		    array('sendMail','boolean','on'=>self::SCENARIO_ANSWER),
		);
	
		if(Yii::app()->user->getIsGuest())
		{
			$rules[]=array('userName','required');
			$rules[]=array('userName','length','max'=>64);
			$rules[]=array('userEmail','email');
			$rules[]=array('userEmail','length','max'=>128);
			$rules[]=array('captcha','captcha');
		}
		return $rules;
	}
	
	public function attributeLabels()
	{
		return array(
		    'title'=>'Тема',
		    'question'=>'Сообщение',
		    'answer'=>'Ответ',
		    'qTime'=>'Время создания сообщения',
		    'aTime'=>'Время ответа',
		    'publish'=>'Опубликовано',
		    'userName'=>'Имя пользователя',
		    'userEmail'=>'e-mail пользователя',
		    'captcha'=>'Проверочный код',
		    'sendMail'=>'Отправить ответ на email',
	    );
	}
	
	public function relations()
	{
		return array(
		    'author'=>array(self::BELONGS_TO,'User','userId'),
			'answerAuthor'=>array(self::BELONGS_TO,'User','aUserId'),
		    'block'=>array(self::BELONGS_TO,'FaqBlock','blockId'),		
		);
	}

	protected function beforeValidate()
	{
		switch ($this->getScenario())
		{
			case self::SCENARIO_QUESTION:
				$this->qTime=new CDbExpression('now()');
				if(!Yii::app()->user->getIsGuest())
		            $this->userId=Yii::app()->user->id;
				break;
			case self::SCENARIO_ANSWER:
				$this->aTime=new CDbExpression('now()');
				$this->aUserId=Yii::app()->user->id;
				break;
		}
		return true;
	}
	
	protected function afterSave()
	{
		switch ($this->getScenario())
		{
			case self::SCENARIO_ANSWER:
				$this->sendMail();
				break;
		}
		return parent::afterSave();
	}
	
	protected function sendMail()
	{
		if ($this->userEmail()==null || !$this->sendMail)
			return;
		
		Yii::import('application.extensions.cmsMailer');
		
		$mailer=new cmsMailer();
		$mailer->theme=Yii::app()->name.': ответ на Ваше сообщение';
		$mailer->to=$this->userEmail();
		$mailer->content=array(
			'model'=>$this,
		);
		$mailer->send();
	}
	
	public function getShortQuestion()
	{
		if(strlen($this->question)<self::SHORT_QUESTION )
			return $this->question;
		$words=preg_split('/\s/', $this->question);
		$l=0;
		$str='';
		foreach ($words as $w) 
		{
			$l+=strlen($w)+1;
			if(($l>self::SHORT_QUESTION-1))
				continue;
			$str.=$w.' ';
		}
		return $str.'...';
	}
	
	public function displayUserName()
	{
		if($this->author)
			return $this->author->username;
		return $this->userName;
	}
	
	public function userEmail()
	{
		if($this->author)
			return $this->author->email;
		return $this->userEmail; 
	}
	
	public function hasAnswer()
	{
		return $this->answer!=null;
	}
}
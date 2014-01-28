<?php

class Question extends CActiveRecord
{
	protected $maxVote=NULL;
	protected $votesCount=null;
			
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
		return 'Vote_Questions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		    array('text', 'required'),
		    array('active', 'boolean'),
		);
	}
	
	public function safeAttributes()
	{
		return array('text','active');
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		    'answers' => array(self::HAS_MANY, 'Answer', 'questionId'),
		);
	}	
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
		    'text' => 'Вопрос',
		    'active' => 'Активный',
		    'updateTime' =>'Время последнего обновления'
		);
	}
	
	protected function beforeValidate($scenario)
	{
		$this->updateTime = new CDbExpression('now()');
		if($this->active)
		{
			Question::model()->updateAll(array('active'=>false),new CDbCriteria(array('active'=>true)));
		}
		return true;
	}
	
	protected function afterSave()
	{
	}
	
    protected function afterDelete()
    {
    	foreach ($this->answers as $answer) {
    		$answer->delete();
    	}
    }
       
    public function isVoted()
    {
    	$userIp = Yii::app()->getRequest()->getUserHostAddress();
    	$cri = new CDbCriteria();
    	$cri->select='ip';
    	$cri->join='inner join '.Answer::model()->tableName().' on questionId='.$this->id;
    	$cri->addColumnCondition(array('ip'=>$userIp));
    	$cri->limit=1;
    	return Vote::model()->find($cri)!=false;
    }
    
    public function maxVote()
    {
    	if($this->maxVote == null)
    	{
    		$this->generateStats();
    	}
    	return (integer) $this->maxVote;    	
    }
    
    public function getVotesCount()
    {
    	if($this->votesCount == null)
    	{
	    	$this->generateStats();
    	}
    	return  (integer) $this->votesCount;    	
    }
    
    protected function generateStats()
    {
    	foreach ($this->answers as $answer)
    	{
    		$count = count($answer->votes);
    		$this->votesCount+=$count;
    		if($count>$this->maxVote)
    		    $this->maxVote = $count; 
    	}
    }
}
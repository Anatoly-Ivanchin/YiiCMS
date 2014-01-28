<?php

class Answer extends CActiveRecord
{
	private $_votesCount=null;
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
		return 'Vote_Answers';
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
		    array('text', 'length', 'max'=>512),
		);
	}
	
	public function safeAttributes()
	{
		return array('text');
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		    'question' => array(self::BELONGS_TO, 'Question', 'questionId'),
		    'votes' => array(self::HAS_MANY, 'Vote', 'answerId'),
		);
	}	
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
		    'text' => 'Текст ответа',
		    'priority' => 'Порядок вывода',
		);
	}
	
    protected function afterDelete()
    {
        foreach ($this->votes as $vote)
        {
        	$vote->delete();
        }
    }
    
    public function getVotesCount()
    {
    	if($this->_votesCount==null)
    	{
    		$this->_votesCount=count($this->votes);
    	}
    	return $this->_votesCount;
    }
    
    public function getPercent()
    {
    	$maxVote = $this->question->maxVote();
    	$vote = $this->getVotesCount();
    	return !$maxVote?0:$vote/$maxVote*100;
    }
    
    public function getAbsolutePercent()
    {
    	$voteCount = $this->question->getVotesCount();
    	$vote = $this->getVotesCount();
    	return !$voteCount?0:round($vote/$voteCount*100);
    }
}
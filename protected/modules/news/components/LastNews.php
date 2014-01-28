<?php

class LastNews extends cmsWidget
{
	protected $count=4;
	protected $title;
	public $newsBlockId = null;
	
	public function getCount()
	{
		return $this->count;
	}
	
	public function setCount($value)
	{
		$this->count=$value;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function setTitle($value)
	{
		$this->title=$value;
	}

	public function run()
	{
		$block=NewsBlock::model()->findByPk($this->newsBlockId);
		if($block==null)
			return;
		$criteria = new CDbCriteria();
		$criteria->order='createTime DESC';
		$criteria->condition='published=TRUE';
		$criteria->limit=$this->count;
		if($this->newsBlockId!=null)
		    $criteria->addColumnCondition(array('newsBlockId'=>$this->newsBlockId));
		
		$lastNews=News::model()->findAll($criteria);
		    
		$this->render('lastNews',array(
			'dataSet'=>$lastNews,
			'block'=>$block,
			'title'=>$this->title
		));
	}
}
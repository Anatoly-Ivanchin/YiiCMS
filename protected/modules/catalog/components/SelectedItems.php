<?php

class SelectedItems extends cmsWidget
{
	public $count=3;
	public $rnd=false;

	public function run()
	{
		$criteria = new CDbCriteria();
		$criteria->condition='marked=TRUE';
		$criteria->limit=$this->count;
		if($this->rnd)
			$criteria->order='RAND()';
		else
			$criteria->order='updateDate DESC';
		
		$items=Items::model()->findAll($criteria);
		    
		$this->render('selectedItems',array(
			'dataSet'=>$items,
		));
	}
}
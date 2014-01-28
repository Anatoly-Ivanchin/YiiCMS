<?php

class CatalogBreadCrumbs extends CWidget
{
	public $model;
	public $splitter="/";
	
	protected $chain=null;
	
	public function init()
	{
		if($this->model instanceof Items)
			$this->chain=$this->model->category->getCategoriesChain();
		if($this->model instanceof Categories)
			$this->chain=$this->model->getCategoriesChain(false);
	}

	public function run()
	{
		echo CHtml::openTag('div',array('id'=>'breadcrumbs'));
		foreach ($this->chain as $item)
		{
			echo CHtml::link($item->title, $item->getFullUrl());
			echo CHtml::tag('span', array('class'=>'splitter'),$this->splitter);
		}
		echo CHtml::tag('span', array(),$this->model->title);
		echo CHtml::closeTag('div');
	}
}
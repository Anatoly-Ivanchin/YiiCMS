<?php

class NewsTags extends CWidget
{
	public $news = null;
	public $tagGetVar='tag';
	
	protected $tags=null;
	protected $block=null;
	
	public function run()
	{
		if(!$this->getBlock()->allowTags)
			return;
		$tags=array();
		foreach ($this->getTags() as $tag)
		{
			$url=array('block/show','url'=>$this->getBlock()->url,$this->tagGetVar=>$tag->id);
			$tags[]=CHtml::link($tag->name,$url);
		} 
		if(count($tags)) 
			$this->render('newstags',array('tagsLinks'=>implode(', ',$tags)));
	}
	
	protected function getBlock()
	{
		if($this->block==null)
			$this->block=$this->news->newsblock;
		return $this->block;
	}
	
	protected function getTags()
	{
		if($this->tags==null)
			$this->tags=$this->news->tags;
		return $this->tags;
	}
}
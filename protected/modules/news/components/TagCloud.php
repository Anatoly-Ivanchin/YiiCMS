<?php

abstract class TagCloud extends CWidget
{
	public $separator=' ';
	public $newsBlock;
	
	public function init()
	{
	}
	
	protected function getData()
	{
		
		$criteria=new CDbCriteria(array(
			'select'=>'t.id, t.name, COUNT(newsId) as weight',
			'join'=>'INNER JOIN News_Tag ON t.id=News_Tag.tagId INNER JOIN News ON News_Tag.newsId=News.id',
			'group'=>'t.name, t.id',
			'condition'=>'News.newsBlockId=:blockId',
			'order'=>'weight DESC, name ASC',
			'params'=>array(':blockId'=>$this->newsBlock->url)
		));

		return Tag::model()->getCommandBuilder()->createFindCommand(Tag::model()->getTableSchema(),$criteria)->queryAll();
	}
	
	protected function createLink($row)
	{
		return array('/news/block/show','tag'=>$row['id'],'url'=>$this->newsBlock->url);
	}
	
	public function run()
	{
		if(!$this->newsBlock->allowTags)
			return;
		$tags=$this->getTags();
		if(count($tags))
		$this->render('tagcloud',array('tags_cloud'=>implode($this->separator,$tags)));
	}
}
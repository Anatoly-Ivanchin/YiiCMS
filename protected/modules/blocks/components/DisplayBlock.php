<?php

class DisplayBlock extends CWidget
{
	public $ident=null;

	public function run()
	{
		$block=Block::model()->findByPk($this->ident);
		if ($block==null)
		{
		    $block = new Block();
		    $block->ident=$this->ident;
		    $block->title=$this->ident;
		    $save = $block->save();
		}
		
		if(Yii::app()->getUser()->checkAccess('admin'))
			$this->regScript($block);
		
		$this->render('display',array('model'=>$block));
	}
	
	protected function regScript($block)
	{
		$url=Yii::app()->getUrlManager()->createUrl('/blocks/default/display',array('id'=>$block->ident));
		$js =<<<EOP
wrapper=jQuery("#block_{$block->ident}_content");
wrapper.load("$url");
EOP;
		$this->widget('FormWindow',array(
			'afterClose'=>$js,
			'selector'=>'#block_'.$block->ident.' .admin_actions a'
		));
	}
}
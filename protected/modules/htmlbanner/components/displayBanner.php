<?php

class displayBanner extends cmsWidget
{
	public $script=null;
	
	public function run()
	{
		$this->regScripts();
		$models=BannerPage::model()->findAll();
		    
		$this->render('banner',array(
			'dataSet'=>$models,
		));
	}
	
	protected function regScripts()
	{
		$baseDir = dirname(__FILE__);
		if($this->script===null)
			$this->script = Yii::app()->getAssetManager()->publish($baseDir.DIRECTORY_SEPARATOR.'js/htmlbanner.js');

        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($this->script,CClientScript::POS_END);
	}
}
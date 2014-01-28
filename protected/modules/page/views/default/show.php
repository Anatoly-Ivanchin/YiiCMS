<?php if($this->beginCache('contentPage_'.$page->url, array('duration'=>3600,
                                             'dependency'=>array(
                                                   'class'=>'system.caching.dependencies.CDbCacheDependency',
                                                   'sql'=>'select updateTime from Page where id='.$page->id)
                                              ))):?>
<div id="page_<?php echo $page->id;?>">
	<h1 id="pageTitle"><?php echo $page->title;?></h1>
	<div class="page_content"><?php echo $page->content; ?></div>
</div>
<?php $this->endCache(); endif; ?>
<?php $this->widget('AdminActions',
	array(
		'urlParams'=>array('url'=>$page->url),
	)
); ?>
<h1 id="pageTitle"><?php echo $model->title;?></h1>
<div class="news_header">
	<span class="news_date"><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['news'], $model->createTime); ?></span>
</div>
<div class="news_content"><?php echo $model->content;?></div>
<?php $this->widget('NewsTags',array('news'=>$model));?>
<?php $this->widget('AdminActions',
	array(
		'urlParams'=>array('id'=>$model->id),
	)
); ?>
<?php if($model->newsblock->allowComments):?>
	<div id="news_comments"></div>
	<script>
		jQuery("#news_comments").load("<?php echo $this->createUrl('comments/show')?>",{"news":<?php echo $model->id?>});
	</script>
<?php endif;?>
<div class="simple">
<?php echo CHtml::activeLabelEx($news,'title'); ?>
<?php echo CHtml::activeTextField($news,'title',array('maxlength'=>128)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($news,'description'); ?>
<?php echo CHtml::activeTextArea($news,'description'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($news,'published'); ?>
<?php echo CHtml::activeCheckBox($news,'published'); ?>
</div>
<?php if($news->newsblock->allowTags): ?>
	<div class="simple">
	<?php echo CHtml::activeLabelEx($news,'tagStr'); ?>
	<?php echo CHtml::activeTextField($news,'tagStr'); ?>
	</div>
<?php endif;?>
<div class="simple">
<?php echo CHtml::activeLabelEx($news,'newsBlockId'); ?>
<?php echo CHtml::activeDropDownList($news,'newsBlockId',$news->getBlocksList(),array(
	'ajax'=>array(
		'url'=>array('create'),
		'type'=>'get',
		'data'=>array('block'=>'js:this.value'),
		'update'=>'#fancybox-content'
	)
)); ?>
</div>
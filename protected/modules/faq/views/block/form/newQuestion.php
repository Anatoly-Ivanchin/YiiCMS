<div id="faq_new_question" class="form">
	<?php echo CHtml::beginForm();?>
		<?php echo CHtml::hiddenField('block',$model->blockId);?>
		<?php echo CHtml::errorSummary($model); ?>
		<div class="row">
		<?php echo CHtml::activeLabelEx($model,'title'); ?>
		<?php echo CHtml::activeTextField($model,'title'); ?>
		</div>
		<div class="row">
		<?php echo CHtml::activeLabelEx($model,'question'); ?>
		<?php echo CHtml::activeTextArea($model,'question'); ?>
		</div>
		<div class="button row">
		<?php echo CHtml::ajaxSubmitButton('Отправить',array(),array('update'=>'#content'),array('class'=>'button'));?>
		</div>
	<?php echo CHtml::endForm();?>
</div>
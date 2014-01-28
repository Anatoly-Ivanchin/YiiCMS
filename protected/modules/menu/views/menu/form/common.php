<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($model); ?>

<div class="simple">
<?php echo CHtml::activeLabelEx($model,'ident'); ?>
<?php echo CHtml::activeTextField($model,'ident',array('maxlength'=>4)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'description'); ?>
<?php echo CHtml::activeTextField($model,'description',array('maxlength'=>64)); ?>
</div>

<div class="simple action">
<?php $this->renderPartial('application.views.forms.buttonsets.savecancel');?>
</div>

<?php echo CHtml::endForm(); ?>
<!-- form -->
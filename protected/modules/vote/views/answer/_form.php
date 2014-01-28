<div class="form">

<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($model); ?>

<div class="row">
<?php echo CHtml::activeLabelEx($model,'text'); ?>
<?php  $this->widget('application.extensions.ckeditor.CKEditor', array(
'model'=>$model,
'attribute'=>'text',
)); ?>
</div>

<div class="simple action">
<?php echo CHtml::submitButton($update ? 'Сохранить' : 'Создать'); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->
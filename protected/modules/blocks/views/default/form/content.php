<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($model); ?>

<div class="row" style="width:894px;">
<?php  $this->widget('application.extensions.ckeditor.CKEditor', array(
'model'=>$model,
'attribute'=>'content',
)); ?>
</div>

<div class="simple action">
<?php $this->renderPartial('application.views.forms.buttonsets.savecancel');?>
</div>

<?php echo CHtml::endForm(); ?>
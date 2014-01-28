<div class="row">
<?php echo CHtml::activeLabelEx($model,'description'); ?>
<?php  $this->widget('application.extensions.ckeditor.CKEditor', array(
'model'=>$model,
'attribute'=>'description',
)); ?>
</div>
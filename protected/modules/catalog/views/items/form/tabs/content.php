<div class="row">
<?php echo CHtml::activeLabelEx($item,'description'); ?>
<?php  $this->widget('application.extensions.ckeditor.CKEditor', array(
'model'=>$item,
'attribute'=>'description',
)); ?>
</div>
<div class="row">
<?php echo CHtml::activeLabelEx($cat,'description'); ?>
<?php  $this->widget('application.extensions.ckeditor.CKEditor', array(
'model'=>$cat,
'attribute'=>'description',
)); ?>
</div>
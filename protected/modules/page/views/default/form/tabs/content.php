<div class="row">
<?php echo CHtml::activeLabelEx($page,'content'); ?>
<?php  $this->widget('application.extensions.ckeditor.CKEditor', array(
'model'=>$page,
'attribute'=>'content',
)); ?>
</div>
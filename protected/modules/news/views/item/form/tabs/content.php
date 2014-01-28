<div class="row">
<?php echo CHtml::activeLabelEx($news,'content'); ?>
<?php  $this->widget('application.extensions.ckeditor.CKEditor', array(
'model'=>$news,
'attribute'=>'content',
)); ?>
</div>
<div class="row">
<?php echo CHtml::activeLabelEx($question,'answer'); ?>
<?php  $this->widget('application.extensions.ckeditor.CKEditor', array(
'model'=>$question,
'attribute'=>'answer',
)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($question,'sendMail'); ?>
<?php echo CHtml::activeCheckBox($question,'sendMail'); ?>
</div>
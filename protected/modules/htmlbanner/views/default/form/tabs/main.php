<div class="simple">
<?php echo CHtml::activeLabelEx($news,'title'); ?>
<?php echo CHtml::activeTextField($news,'title',array('maxlength'=>128)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($news,'description'); ?>
<?php echo CHtml::activeTextArea($news,'description'); ?>
</div>
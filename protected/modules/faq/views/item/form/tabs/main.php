<div class="simple">
<?php echo CHtml::activeLabelEx($question,'title'); ?>
<?php echo CHtml::activeTextField($question,'title'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($question,'question'); ?>
<?php echo CHtml::activeTextArea($question,'question',array('disabled'=>'disabled')); ?>
</div>
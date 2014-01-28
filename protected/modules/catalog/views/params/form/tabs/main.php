<div class="simple">
<?php echo CHtml::activeLabelEx($param,'name'); ?>
<?php echo CHtml::activeTextField($param,'name',array('maxlength'=>255)); ?>
</div>

<div class="simple">
<?php echo CHtml::activeLabelEx($param,'unit'); ?>
<?php echo CHtml::activeTextField($param,'unit',array('maxlength'=>255)); ?>
</div>

<div class="simple">
<?php echo CHtml::activeLabelEx($param,'inputType'); ?>
<?php echo CHtml::activeDropDownList($param,'inputType',$param->getInputTypes()); ?>
</div>
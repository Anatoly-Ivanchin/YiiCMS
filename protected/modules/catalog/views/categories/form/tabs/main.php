<div class="simple">
<?php echo CHtml::activeLabelEx($cat,'title'); ?>
<?php echo CHtml::activeTextField($cat,'title',array('maxlength'=>255)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($cat,'url'); ?>
<?php echo CHtml::activeTextField($cat,'url',array('maxlength'=>64)); ?>
</div>
<?php if($cat->mustHaveParent()):?>
<div class="simple">
<?php echo CHtml::activeLabelEx($cat,'parentId'); ?>
<?php echo CHtml::activeDropDownList($cat,'parentId',$cat->getAllCategories()) ?>
</div>
<?php endif;?>
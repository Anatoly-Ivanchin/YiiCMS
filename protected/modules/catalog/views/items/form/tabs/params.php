<div id="form_custom_params">
</div>
<hr />
<?if($this->getModule()->itemsPrice): ?>
<div class="simple">
<?php echo CHtml::activeLabelEx($item,'price'); ?>
<?php echo CHtml::activeTextField($item,'price') ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($item,'oldPrice'); ?>
<?php echo CHtml::activeTextField($item,'oldPrice') ?>
</div>
<?php endif; ?>
<?if($this->getModule()->itemsCount): ?>
<div class="simple">
<?php echo CHtml::activeLabelEx($item,'count'); ?>
<?php echo CHtml::activeTextField($item,'count') ?>
</div>
<?php endif; ?>
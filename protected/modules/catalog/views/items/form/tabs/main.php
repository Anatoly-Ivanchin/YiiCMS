<div class="simple">
<?php echo CHtml::activeLabelEx($item,'categoryId'); ?>
<?php $data ='param:this.value'.
      $data.=!$item->isNewRecord?',item:'.$item->id:'';
      echo CHtml::activeDropDownList($item,'categoryId',$this->getCategories(),
      array('onchange'=>'jQuery("#form_custom_params").load("'.$this->createUrl('/catalog/items/params').'",{'.$data.'});')) ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($item,'title'); ?>
<?php echo CHtml::activeTextField($item,'title') ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($item,'url'); ?>
<?php echo CHtml::activeTextField($item,'url') ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($item,'shortDescription'); ?>
<?php echo CHtml::activeTextArea($item,'shortDescription') ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($item,'marked'); ?>
<?php echo CHtml::activeCheckBox($item,'marked') ?>
</div>
<script>
jQuery("#Items_categoryId").trigger('change');
</script>
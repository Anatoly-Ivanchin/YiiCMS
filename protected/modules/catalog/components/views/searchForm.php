<div id="catalog_search_form">
    <?php echo CHtml::beginForm(array('/catalog/search'),'get'); ?>
    <?php echo CHtml::textField('searchpattern','�����',array('onfocus'=>'this.value=""','onblur'=>'this.value="�����"')); ?>
    <?php // echo CHtml::submitButton('������',array('name'=>''))?>
    <?php echo CHtml::endForm();?>
</div>

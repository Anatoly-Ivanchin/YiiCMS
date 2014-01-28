<div id="catalog_search_form">
    <?php echo CHtml::beginForm(array('/catalog/search'),'get'); ?>
    <?php echo CHtml::textField('searchpattern','поиск',array('onfocus'=>'this.value=""','onblur'=>'this.value="поиск"')); ?>
    <?php // echo CHtml::submitButton('Искать',array('name'=>''))?>
    <?php echo CHtml::endForm();?>
</div>

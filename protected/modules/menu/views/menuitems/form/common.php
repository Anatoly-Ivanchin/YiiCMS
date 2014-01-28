<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($model); ?>

<?php $this->widget('CTabView',array(
	'id'=>'formTabs',
	'tabs'=>array(
		't1'=>array(
			'title'=>'Основное',
			'view'=>'form/tabs/main'
		),
		't2'=>array(
			'title'=>'Иконка',
			'view'=>'form/tabs/icon'
		),
	),
	'viewData'=>array('item'=>$model),
));?>

<div class="simple action">
<?php $this->renderPartial('application.views.forms.buttonsets.savecancel');?>
</div>

<?php echo CHtml::endForm(); ?>
<!-- form -->
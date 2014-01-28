<?php echo CHtml::beginForm('','post',array('enctype'=>'application/x-www-form-urlencoded')); ?>

<?php echo CHtml::errorSummary($model); ?>

<?php $this->widget('CTabView',array(
	'id'=>'formTabs',
	'tabs'=>array(
		't1'=>array(
			'title'=>'Основное',
			'view'=>'form/tabs/main'
		),
		't3'=>array(
			'title'=>'Изображение',
			'view'=>'form/tabs/picture'
		),
	),
	'viewData'=>array('news'=>$model),
));?>

<div class="simple action">
<?php $this->renderPartial('application.views.forms.buttonsets.savecancel');?>
</div>

<?php echo CHtml::endForm(); ?>
<!-- form -->
<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($model); ?>
<?php echo CHtml::errorSummary($model->getMeta()); ?>

<?php $this->widget('CTabView',array(
	'id'=>'formTabs',
	'tabs'=>array(
		't1'=>array(
			'title'=>'Основное',
			'view'=>'form/tabs/main'
		),
		't2'=>array(
			'title'=>'Содержание',
			'view'=>'form/tabs/content'
		),
		't3'=>array(
			'title'=>'Мета данные страницы',
			'view'=>'application.views.forms.meta'
		),
	),
	'viewData'=>array('page'=>$model,'meta'=>$model->getMeta()),
));?>

<div class="simple action">
<?php $this->renderPartial('application.views.forms.buttonsets.savecancel');?>
</div>

<?php echo CHtml::endForm(); ?>
<!-- form -->
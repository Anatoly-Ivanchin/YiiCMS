<?php $this->pageTitle='Удаление опроса'; ?>

<?php $this->widget('BreadCrumbs',
	array(
		'pages'=>array(
			'Управление сайтом'=>array('/users/default/siteManagment'),
			'Управление опросами'=>array('admin')
		)
	)
); ?>

<div class="form">
<?php echo CHtml::beginForm(); ?>

<p class="hint">Удалить опрос "<?php echo strip_tags($model->text)?>" ?</p>

<div class="row action">
<?php echo CHtml::submitButton('Удалить', array('name'=>'submit')); ?>
<?php echo CHtml::submitButton('Отмена', array('name'=>'cancel')); ?>
</div>

<?php echo CHtml::endForm(); ?>
</div>
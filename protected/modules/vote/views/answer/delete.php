<?php $this->pageTitle='Удаление'; ?>

<?php $this->widget('BreadCrumbs',
	array(
		'pages'=>array(
			'Управление сайтом'=>array('/users/default/siteManagment'),
			'Управление опросами'=>array('question/admin'),
			'Ответы к опросу "'.substr(strip_tags($model->question->text),0,30).'..."'=>array('admin','qid'=>$model->question->id),
		)
	)
); ?>

<div class="form">
<?php echo CHtml::beginForm(); ?>

<p class="hint">Удалить ответ "<?php echo strip_tags($model->text)?>" к опросу "<?php echo strip_tags($model->question->text)?>" ?</p>

<div class="row action">
<?php echo CHtml::submitButton('Удалить', array('name'=>'submit')); ?>
<?php echo CHtml::submitButton('Отмена', array('name'=>'cancel')); ?>
</div>

<?php echo CHtml::endForm(); ?>
</div>
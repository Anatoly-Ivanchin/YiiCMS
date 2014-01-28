<?php $this->pageTitle='Редактирование'; ?>

<?php $this->widget('BreadCrumbs',
	array(
		'pages'=>array(
			'Управление сайтом'=>array('/users/default/siteManagment'),
			'Управление опросами'=>array('question/admin'),
			'Ответы к опросу "'.substr(strip_tags($model->question->text),0,30).'..."'=>array('admin','qid'=>$model->question->id),
		)
	)
); ?>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>true,
)); ?>
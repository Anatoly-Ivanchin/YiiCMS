<?php $this->pageTitle='Ответы к опросу "'.substr(strip_tags($question->text),0,30).'..."'; ?>

<?php $this->widget('BreadCrumbs',
	array(
		'pages'=>array(
			'Управление сайтом'=>array('/users/default/siteManagment'),
			'Управление опросами'=>array('question/admin'),
		)
	)
); ?>

<div id="dataset"><?php echo $dataGrid;?></div>

<?php $this->widget('CLinkPager',array(
    'pages'=>$pages,
)); ?>

<h2>Добавить ответ</h2>
<?php $this->renderPartial('_form',array('update'=>false,'model'=>$newModel)); ?>
<?php $this->pageTitle='Редактирование элемента каталога "'.$item->title.'"'; ?>

<?php $this->widget('BreadCrumbs',
	array(
		'pages'=>array(
			'Управление сайтом'=>array('/users/default/siteManagment'),
			'Управление разделами каталога'=>array('categories/admin'),
			'Элементы раздела "'.$item->category->title.'"'=>array('admin','cid'=>$item->category->id),
		)
	)
); ?>

<?php $this->renderPartial('_form',array('update'=>true,'item'=>$item,'defaultCat'=>$item->category)); ?>
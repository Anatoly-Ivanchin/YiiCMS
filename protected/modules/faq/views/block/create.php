<h2>Новый пост</h2>

<div class="actionBar">
[<?php echo CHtml::link('Список постов',array('list')); ?>]
[<?php echo CHtml::link('Управление постами',array('admin')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>false,
)); ?>
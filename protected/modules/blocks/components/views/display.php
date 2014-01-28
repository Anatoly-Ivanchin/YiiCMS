<div id="block_<?php echo $model->ident ?>" class="block">
    <?php $this->widget('AdminActions',
	array(
		'urlParams'=>array('id'=>$model->ident),
		'actions'=>array(
			'edit'=>array('url'=>array('/blocks/default/content','id'=>$model->ident)), 
			'delete'=>1,
	)
	)
); ?><div id="block_<?php echo $model->ident ?>_content">
	    <?php $this->getController()->renderPartial('blocks.views.default.display',array('block'=>$model))?>
    </div>
</div>

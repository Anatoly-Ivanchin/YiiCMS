<?php echo CHtml::beginForm();?>
	<p>Удалить коментарий?</p>
	<div class="simple">
	<?php echo CHtml::ajaxSubmitButton('Отмена',
		array('delete'),array('update'=>'#nc_'.$comment->id,'data'=>array('cancel'=>true,'id'=>$comment->id)),
		array('id'=>'cancel_delete_news_coment_'.$comment->id,'class'=>'button'));?>
	<?php echo CHtml::ajaxSubmitButton('Удалить',
		array('delete'),array('update'=>'#nc_'.$comment->id,'data'=>array('delete'=>true,'id'=>$comment->id)),
		array('id'=>'delete_news_coment_'.$comment->id,'class'=>'button'));?>
	</div>
<?php echo CHtml::endForm();?>
<div class="form">
<?php echo CHtml::beginForm();?>
	<?php echo CHtml::hiddenField('id',$comment->id);?>
	<?php $this->renderPartial('_form',array('comment'=>$comment));?>
	<div class="simple action">
	<?php echo CHtml::ajaxSubmitButton('Отмена',
		array('edit'),array('update'=>'#nc_'.$comment->id,'data'=>array('cancel'=>true,'id'=>$comment->id)),
		array('id'=>'cancel_news_coment_'.$comment->id,'class'=>'button'));?>
	<?php echo CHtml::ajaxSubmitButton('Отправить',
		array('edit'),array('update'=>'#nc_'.$comment->id),
		array('id'=>'edit_news_coment_'.$comment->id,'class'=>'button'));?>
	</div>
<?php echo CHtml::endForm();?>
</div>
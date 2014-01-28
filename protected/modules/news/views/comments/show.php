<?php foreach ($comments as $comment):?>
<div id="nc_<?php echo $comment->id?>" class="news_comment">
<?php $this->renderPartial('_item',array('comment'=>$comment));?>
</div>
<?php endforeach;?>
<div id="news_new_comment">
<div class="commentform">
<?php echo CHtml::beginForm();?>
	<?php echo CHtml::hiddenField('news',$newcomment->newsId);?>
	<?php $this->renderPartial('_form',array('comment'=>$newcomment));?>
	<div class="simple action">
	<?php echo CHtml::ajaxSubmitButton('Отправить',array('show'),array('update'=>'#news_comments'),array('id'=>'edit_news_coment_new','class'=>'button'));?>
	</div>
<?php echo CHtml::endForm();?>
</div>
</div>
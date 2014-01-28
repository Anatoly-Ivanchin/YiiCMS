<div class="comment_info">
	<div class="comment_author"><?php echo $comment->getUserName()?></div>
	<div class="comment_time"><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['comments'], $comment->updateTime); ?></div>
</div>
<div class="comment_text"><?php echo CHtml::encode($comment->text); ?></div>
<?php if(Yii::app()->user->id==$comment->userId && $comment->userId!=null|| Yii::app()->user->checkAccess('admin')):?>
	<div class="comment_actions">
		<?php echo CHtml::link('<img src="'.AdminActions::ICON_PATH.'edit.png" alt="Редактировать" title="Редактировать"/>','#',
			array('onclick'=>'js:event.preventDefault();'.chtml::ajax(
				array(
					'update'=>'#nc_'.$comment->id,
					'url'=>array('edit'),
					'type'=>'post',
					'data'=>array('id'=>$comment->id),
				)
			)));
		echo CHtml::link('<img src="'.AdminActions::ICON_PATH.'delete.png" alt="Удалить" title="Удалить"/>','#',
			array('onclick'=>'js:event.preventDefault();'.chtml::ajax(
				array(
					'update'=>'#nc_'.$comment->id,
					'url'=>array('delete'),
					'type'=>'post',
					'data'=>array('id'=>$comment->id),
				)
			))); ?>
	</div>
<?php endif;?>
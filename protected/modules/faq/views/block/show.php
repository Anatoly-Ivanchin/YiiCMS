<h1 id="pageTitle"><?php echo $block->title;?></h1>
<div class="faq_block_description">
	<?php echo $block->description?>
</div>
<?php $this->widget('AdminActions',
	array(
		'urlParams'=>array('id'=>$block->id)
	)
); ?>
<div class="faq_list">
	<?php foreach ($questions as $item):?>
		<div id="faq_"<?php echo $item->id;?>>
			<h2><?php echo CHtml::link(CHtml::encode($item->title),array('item/show','url'=>$block->url,'id'=>$item->id));?></h2>
			<div class="faq_question"><?php echo CHtml::encode($item->getShortQuestion());?></div>
			<div class="faq_info">
				<div class="faq_time"><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['faq'], $item->qTime); ?></div>
				<div class="faq_user"><?php echo $item->displayUserName();?></div>
			</div>
			
			<?php $this->widget('AdminActions',
				array(
					'urlParams'=>array('id'=>$item->id),
					'urlPattern'=>'item/??',
					'actions'=>array('edit'=>array('title'=>'Ответить'))
				)
			); ?>
		</div>
	<?php endforeach;?>
	<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
	<?php if(Yii::app()->user->getIsGuest())
		$this->widget('users.components.UloginWidget',array(
			'showButtunsAlways'=>true,
			'action'=>array('create'),
			'callback'=>login,
		));
	else
		$this->renderPartial('form/newQuestion',array('model'=>$newQ));
	?>
</div>
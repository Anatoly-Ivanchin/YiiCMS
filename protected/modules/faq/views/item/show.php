<h1 id="pageTitle"><?php echo CHtml::encode($model->title);?></h1>
<div class="faq_question"><?php echo CHtml::encode($model->question);?></div>
<div class="faq_info">
	<div class="faq_time"><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['faq'], $model->qTime); ?></div>
	<div class="faq_user"><?php echo $model->displayUserName();?></div>
</div>
<div class="faq_answer"><?php echo $model->answer;?></div>

<?php $this->widget('AdminActions',
	array(
		'urlParams'=>array('id'=>$model->id),
		'actions'=>array('edit'=>array('title'=>'Ответить'))
	)
); ?>

<div id="back_link"><?php echo CHtml::link('Назад', array('block/show','url'=>$model->block->url));?></div>
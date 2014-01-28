<div>На ваше сообщение 
<strong><?php echo CHtml::encode($model->title)?></strong> от 
<span><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['faq'], $model->qTime); ?></span>
<p style="text-align:center;"><?php echo $model->question;?></p>
получен ответ:
<p style="text-align:center;"><?php echo $model->answer;?></p>
<div style="text-align: right">
	<?php echo Yii::app()->name;?>
</div>
</div>
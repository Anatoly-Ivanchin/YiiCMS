<div class="simple">
<?php echo CHtml::activeLabelEx($news,'programmId'); ?>
<?php echo CHtml::activeDropDownList($news, 'programmId', $news->getProgrammsList()); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($news,'schoolId'); ?>
<?php echo CHtml::activeDropDownList($news, 'schoolId', $news->getSchoolsList()); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($news,'eventDate'); ?>
<?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
	$this->widget('CJuiDateTimePicker',array(
		'model'=>$news,
		'attribute'=>'eventDate',
		'options'=>array(
			'timeText'=>'Время',
			'hourText'=>'',
			'minuteText'=>'',
			'stepMinute'=>5,
			'dateFormat'=>'yy-mm-dd',
			'timeFormat'=>'hh:mm:ss'
		)
	));
?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($news,'vacancies'); ?>
<?php echo CHtml::activeTextField($news, 'vacancies'); ?>
</div>
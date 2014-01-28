<style>
<!--
#enroll { list-style-type: none;}
#enroll li { margin-bottom:24px; }
#enroll ul li {margin-bottom:0;}
#enroll strong, #enroll i { margin-right: 24px; } 
-->
</style>
<ul id="enroll">
<li>
	<strong>Урок:</strong>
	<ul>
	<li><i><?php echo $model->schedule->getAttributeLabel('schoolId')?>:</i><span><?php echo $model->schedule->school->name?></span></li>
	<li><i><?php echo $model->schedule->getAttributeLabel('programmId')?>:</i><span><?php echo $model->schedule->programm->name?></span></li>
	<li><i><?php echo $model->schedule->getAttributeLabel('eventDate')?>:</i><span><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['enroll'], $model->schedule->eventDate); ?></span></li>
	</ul>
</li>
<li><strong><?php echo $model->getAttributeLabel('phone')?>:</strong><span><?php echo $model->phone?></span></li>
<li><strong><?php echo $model->getAttributeLabel('email')?>:</strong><span><?php echo CHtml::mailto($model->email); ?></span></li>
<li><strong><?php echo $model->getAttributeLabel('adId')?>:</strong><span><?php echo $model->ad->name?></span></li>
<li><strong><?php echo $model->getAttributeLabel('createTime')?>:</strong><span><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['enroll'], $model->createTime); ?></span></li>
</ul>
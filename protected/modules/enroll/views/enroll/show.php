<style>
<!--
ul { list-style-type: none;}
li { margin-bottom:24px; }
strong { margin-right: 24px; } 
-->
</style>
<ul>
<li><strong><?php echo $model->getAttributeLabel('programmId')?>:</strong><span><?php echo $model->programm->name?></span></li>
<li><strong><?php echo $model->getAttributeLabel('daypartId')?>:</strong><span><?php echo $model->daypart->name ?></span></li>
<li><strong><?php echo $model->getAttributeLabel('schoolId')?>:</strong><span><?php echo $model->school->name ?></span></li>
<li><strong><?php echo $model->getAttributeLabel('phone')?>:</strong><span><?php echo $model->phone?></span></li>
<li><strong><?php echo $model->getAttributeLabel('email')?>:</strong><span><?php echo CHtml::mailto($model->email); ?></span></li>
<li><strong><?php echo $model->getAttributeLabel('adId')?>:</strong><span><?php echo $model->ad->name?></span></li>
<li><strong><?php echo $model->getAttributeLabel('createTime')?>:</strong><span><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['admin'], $model->createTime); ?></span></li>

</ul>
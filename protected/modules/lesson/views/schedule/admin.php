<?php $this->pageTitle='Управление расписанием'; ?>

<table class="dataGrid" cellpadding="0" cellspacing="0">
  <thead>
  <tr>
    <th><?php echo $sort->link('eventDate'); ?></th>
    <th><?php echo $sort->link('programmId'); ?></th>
    <th><?php echo $sort->link('schoolId'); ?></th>
    <th><?php echo $sort->link('vacancies'); ?></th>
	<th>Действия</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['admin'], $model->eventDate); ?></td>
    <td><?php echo $model->programm->name; ?></td>
    <td><?php echo $model->school->name; ?></td>
    <td><?php echo $model->vacancies; ?>/<?php echo CHtml::link($model->enrollsCount,array('enroll/admin','sid'=>$model->id)); ?></td>
    <?php $this->widget('AdminActions',
		array(
			'urlParams'=>array('id'=>$model->id),
			'element'=>'td'
		)
	); ?>
  </tr>
<?php endforeach; ?>
<?php if(count($models)<=0):?>
	<tr><td colspan="1"><p>Список пуст</p></td><td></td></tr>
<?php endif;?>
  </tbody>
</table>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<div class="admin_actions">
	<?php echo CHtml::link('Добавить запись',array('create'), array('class'=>'create')); ?>
</div>
<?php $this->pageTitle='Просмотр заявок'; ?>

<table class="dataGrid" cellpadding="0" cellspacing="0">
  <thead>
  <tr>
    <th><?php echo $sort->link('lastName'); ?></th>
    <th><?php echo $sort->link('createTime'); ?></th>
    <th><?php echo $sort->link('phone'); ?></th>
    <th><?php echo $sort->link('email'); ?></th>
	<th>Действия</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($data as $key=>$model): ?>
  <tr>
    <td><?php echo CHtml::link($model->getFullName(),array('show','id'=>$model->id)); ?></td>
    <td><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['admin'], $model->createTime); ?></td>
    <td><?php echo $model->phone; ?></td>
    <td><?php echo CHtml::mailto($model->email); ?></td>
    <?php $this->widget('AdminActions',
		array(
			'urlParams'=>array('id'=>$model->id),
			'element'=>'td',
			'actions'=>array('edit'=>false)
		)
	); ?>
  </tr>
<?php endforeach;?>
<?php if(count($data)<=0):?>
	<tr><td colspan="4"><p>Список пуст</p></td><td></td></tr>
<?php endif;?>
  </tbody>
</table>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
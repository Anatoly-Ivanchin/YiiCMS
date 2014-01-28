<?php $this->pageTitle='Управление контентными блоками'; ?>

<table class="dataGrid" cellpadding="0" cellspacing="0">
  <thead>
  <tr>
    <th><?php echo $sort->link('ident'); ?></th>
    <th><?php echo $sort->link('title'); ?></th>
    <th><?php echo $sort->link('update_date'); ?></th>
	<th>&nbsp;</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo $model->ident; ?></td>
    <td><?php echo CHtml::encode($model->title); ?></td>
    <td><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['admin'], $model->update_date); ?></td>
    <?php $this->widget('AdminActions',
		array(
			'element'=>'td',
			'urlParams'=>array('id'=>$model->ident),
			'actions'=>array(
				'content'=>array(
					'title'=>'Содержание',
					'icon'=>'list.png',
					'url'=>array('content','id'=>$model->ident)
				)
			),
		)
	); ?>
  </tr>
<?php endforeach; ?>
<?php if(count($models)<=0):?>
	<tr><td colspan="3"><p>Блоков нет</p></td><td></td></tr>
<?php endif;?>
  </tbody>
</table>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
<div class="admin_actions">
	<?php echo CHtml::link('Добавить новый блок',array('create'), array('class'=>'create')); ?>
</div>
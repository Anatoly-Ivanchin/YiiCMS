<?php $this->pageTitle='Управление разделами'; ?>

<table class="dataGrid" cellpadding="0" cellspacing="0">
  <thead>
  <tr>
    <th><?php echo $sort->link('title'); ?></th>
    <th><?php echo $sort->link('url'); ?></th>
    <th><?php echo $sort->link('updateTime'); ?></th>
	<th>Сообщения</th>
	<th>&nbsp;</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link(CHtml::encode($model->title),array('show','url'=>$model->url)); ?></td>
    <td><?php echo CHtml::encode($model->url) ?></td>
    <td><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['admin'], $model->updateTime); ?></td>
    <td><?php echo CHtml::link(
		CHtml::image("/images/admin/actions/list.png",'Список сообщений',array('title'=>'Список сообщений ('.count($model->items).')'))
		,array('item/admin','block'=>$model->id)); ?></td>
      <?php $this->widget('AdminActions',
		array(
			'urlParams'=>array('id'=>$model->id),
			'element'=>'td',
		)
	); ?>
  </tr>
<?php endforeach; ?>
<?php if(count($models)<=0):?>
	<tr><td colspan="4"><p>Разделов нет</p></td><td></td></tr>
<?php endif;?>
  </tbody>
</table>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<div class="admin_actions">
	<?php echo CHtml::link('Добавить новый раздел',array('create'), array('class'=>'create')); ?>
</div>
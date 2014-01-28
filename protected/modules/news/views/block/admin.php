<?php $this->pageTitle='Управление новостными блоками'; ?>

<table class="dataGrid" cellpadding="0" cellspacing="0">
  <thead>
  <tr>
    <th><?php echo $sort->link('title'); ?></th>
    <th><?php echo $sort->link('url'); ?></th>
    <th><?php echo $sort->link('updateTime'); ?></th>
	<th>Управление новостями</th>
	<th>&nbsp;</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link(CHtml::encode($model->title),array('show','url'=>$model->url)); ?></td>
    <td><?php echo CHtml::encode($model->url) ?></td>
    <td><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['admin'], $model->updateTime); ?></td>
    <td><?php echo CHtml::link('Список новостей ('.count($model->news).')',array('item/admin','block'=>$model->url)); ?></td>
      <?php $this->widget('AdminActions',
		array(
			'urlParams'=>array('url'=>$model->url),
			'element'=>'td',
		)
	); ?>
  </tr>
<?php endforeach; ?>
<?php if(count($models)<=0):?>
	<tr><td colspan="4"><p>Новостных блоков нет</p></td><td></td></tr>
<?php endif;?>
  </tbody>
</table>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<div class="admin_actions">
	<?php echo CHtml::link('Добавить новый блок',array('create'), array('class'=>'create')); ?>
</div>
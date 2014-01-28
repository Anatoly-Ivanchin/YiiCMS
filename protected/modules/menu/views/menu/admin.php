<?php $this->pageTitle='Управление меню'; ?>

<table class="dataGrid" cellpadding="0" cellspacing="0">
  <tr>
    <th><?php echo $sort->link('ident'); ?></th>
    <th><?php echo $sort->link('description'); ?></th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::encode($model->ident); ?></td>
    <td><?php echo CHtml::encode($model->description); ?></td>
    <td>
	    <?php echo CHtml::link(CHtml::image("/images/admin/actions/list.png",'Пункты меню',array('title'=>'Пункты меню')),array('menuitems/admin','ident'=>$model->ident));?>
    </td>
    <?php $this->widget('AdminActions',
		array(
			'urlParams'=>array('ident'=>$model->ident),
			'element'=>'td'
		)
	); ?>
  </tr>
<?php endforeach; ?>
<?php if(count($models)<=0):?>
	<tr><td colspan="3"><p>Меню нет</p></td><td></td></tr>
<?php endif;?>
</table>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
<div class="admin_actions">
	<?php echo CHtml::link('Добавить новое меню',array('create'), array('class'=>'create')); ?>
</div>
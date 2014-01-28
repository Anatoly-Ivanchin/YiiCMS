<?php $this->pageTitle='Управление разделами каталога'; ?>

<table class="dataGrid" cellpadding="0" cellspacing="0">
  <tr>
    <th><?php echo $sort->link('title'); ?></th>
    <th><?php echo $sort->link('parentId'); ?></th>
    <th><?php echo $sort->link('updateDate'); ?></th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>
<?php foreach($cats as $n=>$cat): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link(CHtml::encode($cat->title),$cat->fullUrl); ?></td>
    <td><?php echo $cat->parent->title; ?></td>
    <td><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['admin'], $cat->updateDate); ?></td>
    <td>
	    <?php echo CHtml::link(CHtml::image("/images/admin/actions/list.png",'Элементы раздела',array('title'=>'Элементы раздела')),array('items/admin','cid'=>$cat->id));?>
	    <?php echo CHtml::link(CHtml::image("/images/admin/actions/params.png",'Параметры',array('title'=>'Параметры')),array('params/admin','cat'=>$cat->id));?>
	</td>
	<td>
	    <?php echo CHtml::link(CHtml::image("/images/admin/actions/up.png",'Поднять приоритет',array('title'=>'Поднять приоритет')),'#',array('onclick'=>'js:'.$this->upPriorityAjax($cat->id).';'));?>
	    <?php echo CHtml::link(CHtml::image("/images/admin/actions/down.png",'Опустить приоритет',array('title'=>'Опустить приоритет')),'#',array('onclick'=>'js:'.$this->downPriorityAjax($cat->id).';'));?>
    </td>
    <?php $this->widget('AdminActions',
		array(
			'urlParams'=>array('id'=>$cat->id),
			'element'=>'td',
		)
	); ?>
  </tr>
<?php endforeach; ?>
<?php if(count($cats)<=0):?>
	<tr><td colspan="5"><p>Разделов нет</p></td><td></td></tr>
<?php endif;?>
</table>

<?php $this->widget('CLinkPager',array('pages'=>$pages,)); ?>

<div class="admin_actions">
	<?php echo CHtml::link('Добавить новый раздел',array('create'), array('class'=>'create')); ?>
</div>
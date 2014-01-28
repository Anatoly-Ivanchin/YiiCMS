<?php $this->pageTitle=$cat?'Элементы раздела "'.$cat->title.'"':'Управление элементами каталога'; ?>

<table class="dataGrid" cellpadding="0" cellspacing="0">
  <tr>
    <th><?php echo $sort->link('title'); ?></th>
    <th><?php echo $sort->link('categoryId'); ?></th>
    <th><?php echo $sort->link('updateDate'); ?></th>
    <th><?php echo $sort->link('priority'); ?></th>
    <th>&nbsp;</th>
  </tr>
<?php foreach($items as $n=>$item): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link(CHtml::encode($item->title),$item->getFullUrl()); ?></td>
    <td><?php echo CHtml::encode($item->category->title); ?></td>
    <td><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['admin'], $item->updateDate); ?></td>
    <td>
        <?php echo CHtml::link(CHtml::image("/images/admin/actions/up.png",'Поднять приоритет',array('title'=>'Поднять приоритет')),'#',array('onclick'=>'js:event.preventDefault();'.$this->upPriorityAjax($item->id).';'));?>
	    <?php echo CHtml::link(CHtml::image("/images/admin/actions/down.png",'Опустить приоритет',array('title'=>'Опустить приоритет')),'#',array('onclick'=>'js:event.preventDefault();'.$this->downPriorityAjax($item->id).';'));?>

    </td>
    <?php $this->widget('AdminActions',
		array(
			'urlParams'=>array('id'=>$item->id),
			'element'=>'td'
		)
	); ?>
  </tr>
<?php endforeach; ?>
<?php if(count($items)<=0):?>
	<tr><td colspan="4"><p>Элементов нет</p></td><td></td></tr>
<?php endif;?>
</table>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>



<div class="admin_actions">
	<?php echo CHtml::link('Добавить новый элемент',array('create','cid'=>$cat?$cat->id:$defaultCat->id), array('class'=>'create')); ?>
</div>
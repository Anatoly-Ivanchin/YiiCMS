<?php $this->pageTitle='Параметры раздела "'.$cat->title.'"'; ?>

<table class="dataGrid" cellpadding="0" cellspacing="0">
  <tr>
    <th><?php echo $sort->link('name'); ?></th>
    <th><?php echo $sort->link('updateDate'); ?></th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>
<?php foreach($params as $n=>$param): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo $param->name; ?></td>
    <td><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['admin'], $param->updateDate); ?></td>
    <td>
	    <?php echo CHtml::link(CHtml::image("/images/admin/actions/up.png",'Поднять приоритет',array('title'=>'Поднять приоритет')),'#',array('onclick'=>'js:'.$this->upPriorityAjax($param->id).';'));?>
	    <?php echo CHtml::link(CHtml::image("/images/admin/actions/down.png",'Опустить приоритет',array('title'=>'Поднять приоритет')),'#',array('onclick'=>'js:'.$this->downPriorityAjax($param->id).';'));?>
    </td>
    <?php $this->widget('AdminActions',
		array(
			'urlParams'=>array('id'=>$param->id),
			'element'=>'td',
		)
	); ?>
  </tr>
<?php endforeach; ?>
<?php if(count($param)<=0):?>
	<tr><td colspan="4"><p>Параметров нет</p></td><td></td></tr>
<?php endif;?>
</table>

<?php $this->widget('CLinkPager',array('pages'=>$pages,)); ?>

<div class="admin_actions">
	<?php echo CHtml::link('Добавить новый параметр',array('create','cat'=>$cat->id), array('class'=>'create')); ?>
</div>
<?php $this->pageTitle='Управление пунктами меню "'.$menu->description.'"'; ?>
<?php if($parent!==null):?>
<div>
<span>Подменю пункта: </span>
<?php 
$url=array('admin','ident'=>$menu->ident);
if($parent->parentId!=null)
$url['pid']=$parent->parentId;
echo CHtml::link($parent->title, $url)?>
</div>
<?php endif;?>

<table class="dataGrid" cellpadding="0" cellspacing="0">
  <tr>
    <th><?php echo $sort->link('title'); ?></th>
    <th><?php echo $sort->link('url'); ?></th>
    <th><?php echo $sort->link('priority'); ?></th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::encode($model->title); ?></td>
    <td><?php echo CHtml::encode($model->displayUrl()); ?></td>
    <td>
        <?php echo CHtml::link(CHtml::image("/images/admin/actions/up.png",'Поднять приоритет',array('title'=>'Поднять приоритет')),'#',array('onclick'=>'js:event.preventDefault();'.$this->upPriorityAjax($model->id).';'));?>
	    <?php echo CHtml::link(CHtml::image("/images/admin/actions/down.png",'Опустить приоритет',array('title'=>'Опустить приоритет')),'#',array('onclick'=>'js:event.preventDefault();'.$this->downPriorityAjax($model->id).';'));?>
    </td>
    <td>
	    <?php if($model->canHasSubMenu()) 
		    echo CHtml::link(
		    CHtml::image("/images/admin/actions/list.png",'Пункты подменю',array('title'=>'Пункты подменю ('.count($model->children).')'))
		    ,array('menuitems/admin','ident'=>$menu->ident,'pid'=>$model->id));?>
    </td>
    <?php $this->widget('AdminActions',
		array(
			'element'=>'td',
			'urlParams'=>array('id'=>$model->id),
		)
	); ?>
  </tr>
<?php endforeach; ?>
<?php if(count($models)<=0):?>
	<tr><td colspan="3"><p>Пунктов меню нет</p></td><td></td></tr>
<?php endif;?>
</table>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
<div class="admin_actions">
	<?php
	$url=array('create','ident'=>$menu->ident);
	if($parent!==null) $url['pid']=$parent->id;
	echo CHtml::link('Добавить новый пункт меню',$url, array('class'=>'create')); ?>
</div>
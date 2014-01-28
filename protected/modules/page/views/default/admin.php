<?php $this->pageTitle='Управление страницами'; ?>

<table class="dataGrid" cellpadding="0" cellspacing="0">
  <tr>
    <th><?php echo $sort->link('title'); ?></th>
    <th><?php echo $sort->link('url'); ?></th>
    <th><?php echo $sort->link('updateTime'); ?></th>
    <th>&nbsp;</th>
  </tr>
<?php foreach($contentPages as $n=>$page): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link(CHtml::encode($page->title),array('show','url'=>$page->url)); ?></td>
    <td><?php echo $page->url; ?></td>
    <td><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['admin'], $page->updateTime); ?></td>
    <?php $this->widget('AdminActions',
		array(
			'urlParams'=>array('url'=>$page->url),
			'element'=>'td'
		)
	); ?>
  </tr>
<?php endforeach; ?>
<?php if(count($contentPages)<=0):?>
	<tr><td colspan="4"><p>Страниц нет</p></td><td></td></tr>
<?php endif;?>
</table>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
<div class="admin_actions">
	<?php echo CHtml::link('Добавить новую страницу',array('create'), array('class'=>'create')); ?>
</div>
<?php $this->pageTitle='Управление новостями блока "'.CHtml::encode($block->title).'"'; ?>

<table class="dataGrid" cellpadding="0" cellspacing="0">
  <thead>
  <tr>
    <th><?php echo $sort->link('title'); ?></th>
    <th><?php echo $sort->link('updateTime'); ?></th>
    <th><?php echo $sort->link('published'); ?></th>
	<th>Действия</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link(CHtml::encode($model->title),array('show','id'=>$model->id,'url'=>$block->url)); ?></td>
    <td><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['admin'], $model->updateTime); ?></td>
    <td>
      	  <?php $confirm=CJavaScript::quote(($model->published?'Снять с публикации':'Опубликовать')." новость '{$model->title}'?");
		    $ajax='jQuery("#content").load(location.href,{"id":'.$model->id.',"command":"switchpublish"});';
		    echo chtml::activeCheckBox($model,'published',array(
	      	  'onclick'=>'if(confirm(\''.$confirm.'\'))'.$ajax.' else return false;',
	      	  'id'=>'news_'.$model->id.'_switchpublish',
          )); ?>
    </td>
    <?php $this->widget('AdminActions',
		array(
			'urlParams'=>array('id'=>$model->id),
			'element'=>'td'
		)
	); ?>
  </tr>
<?php endforeach; ?>
<?php if(count($models)<=0):?>
	<tr><td colspan="5"><p>Новостей нет</p></td><td></td></tr>
<?php endif;?>
  </tbody>
</table>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<div class="admin_actions">
	<?php echo CHtml::link('Добавить новость',array('create','block'=>$block->url), array('class'=>'create')); ?>
</div>
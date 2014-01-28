<?php $this->pageTitle='Управление сообщениями'; ?>

<div id="admin_filter">
<?php echo CHtml::label('Сообщения без ответов', 'f1');
$ajax='jQuery("#content").load(location.href,"withoutAnswers="+this.checked);';
echo CHtml::checkBox('f1',$this->withoutAnswers, array('onchange'=>$ajax));?>
</div>
<table class="dataGrid" cellpadding="0" cellspacing="0">
  <thead>
  <tr>
    <th><?php echo $sort->link('title'); ?></th>
    <th><?php echo $sort->link('qTime'); ?></th>
    <th><?php echo $sort->link('publish'); ?></th>
	<th>Действия</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link(CHtml::encode($model->title),array('show','id'=>$model->id,'url'=>$block->url)); ?></td>
    <td><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['admin'], $model->qTime); ?></td>
    <td>
      	  <?php $confirm=CJavaScript::quote(($model->publish?'Снять с публикации':'Опубликовать')." сообщение '{$model->title}'?");
		    $ajax='jQuery("#content").load(location.href,{"id":'.$model->id.',"command":"switchpublish"});';
		    echo chtml::activeCheckBox($model,'publish',array(
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
	<tr><td colspan="5"><p>Сообщений нет</p></td><td></td></tr>
<?php endif;?>
  </tbody>
</table>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
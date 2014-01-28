<?php $this->pageTitle='Управление опросами'; ?>

<?php $this->widget('BreadCrumbs',
	array(
		'pages'=>array(
			'Управление сайтом'=>array('/users/default/siteManagment'),
		)
	)
); ?>

<table class="dataGrid">
  <tr>
    <th><?php echo $sort->link('text'); ?></th>
    <th><?php echo $sort->link('updateTime'); ?></th>
    <th>Всего голосов</th>
    <th><?php echo $sort->link('active'); ?></th>
    <th>Действия</th>
  </tr>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo $model->text; ?></td>
    <td><?php echo $model->updateTime; ?></td>
    <td><?php echo $model->getVotesCount();?></td>
    <td><?php echo CHtml::linkButton($model->active?'Да':'Нет',array(
      	  'submit'=>'',
      	  'params'=>array('command'=>'activate','id'=>$model->id),
      	  )); ?></td>
    <td>
        <?php echo CHtml::link('Ответы',array('answer/admin','qid'=>$model->id)); ?>
        <?php echo CHtml::link('Редактировать',array('update','id'=>$model->id)); ?>
        <?php echo CHtml::link('Удалить',array('delete','id'=>$model->id)); ?>
    </td>
  </tr>
<?php endforeach; ?>
</table>

<?php $this->widget('CLinkPager',array(
    'pages'=>$pages,
)); ?>

<h2>Добавить опрос</h2>
<?php $this->renderPartial('_form',array('update'=>false,'model'=>$newModel)); ?>
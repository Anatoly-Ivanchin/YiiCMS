<table class="dataGrid">
  <tr>
    <th><?php echo $sort->link('text'); ?></th>
    <th>Всего голосов</th>
    <th><?php echo $sort->link('priority'); ?></th>
    <th>Действия</th>
  </tr>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo $model->text; ?></td>
    <td><?php echo $model->getVotesCount();?></td>
    <td>
        <?php $this->widget('OrderButtons',array(
             'model_id'=>$model->id,
             'update'=>'#dataset',
             'img_up'=>'/images/up.png',
             'img_down'=>'/images/down.png',
        )); ?>
    </td>
    <td>
        <?php echo CHtml::link('Редактировать',array('update','id'=>$model->id)); ?>
        <?php echo CHtml::link('Удалить',array('delete','id'=>$model->id)); ?>
    </td>
  </tr>
<?php endforeach; ?>
</table>

<?php $this->widget('CLinkPager',array(
    'pages'=>$pages,
)); ?>
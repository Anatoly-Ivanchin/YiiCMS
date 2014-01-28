<table border="1" cellspacing="0" width="100%">
<tr>
<th>Дата</th>
<th>Время</th>
<th>Школа</th>
<th>&nbsp;</th>
</tr>
<?php foreach ($data as $id=>$row):?>
<tr>
<td><?php echo Yii::app()->dateFormatter->format('d MMMM yyyy', $row->eventDate); ?></td>
<td><?php echo Yii::app()->dateFormatter->format('HH:mm', $row->eventDate); ?></td>
<td><?php echo $row->school->name ?></td>
<td><?php echo CHtml::radioButton('Lesson[scheduleId]',null,array('value'=>$row->id))?></td>
</tr>
<?php endforeach; ?>
<?php if(count($data)==0):?>
<tr><td colspan="4"><p></p></td></tr>
<?php endif;?>
</table>
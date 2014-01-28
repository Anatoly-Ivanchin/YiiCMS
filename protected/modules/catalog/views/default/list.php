<?php $this->widget('AdminActions',
array(
	'urlParams'=>array('id'=>$model->id),
	'urlPattern'=>'categories/??',
	'actions'=>array('delete'=>false),
)); ?>
     
<?php if(count($items["models"])>0): ?>
<div id="catalog_items">
<?php foreach($items["models"] as $n=>$model): ?>
	<div id="ci_<?php echo $n ?>" class="catalog_item">
		<div class="picture">
			<?php //echo CHtml::link(CHtml::image($model->getSingleImageUrl('small')),$model->fullUrl);?>
		</div>
		<div class="description">
			<h2 class="title"><?php //echo CHtml::link(CHtml::encode($model->title),$model->fullUrl); ?></h2>
			<div class="text"><?php //echo $model->getAnotation();?></div>
		</div>
		<?php //$this->widget('AdminActions',
		//array(
		//	'urlParams'=>array('id'=>$model->id),
		//	'urlPattern'=>'items/??',
		//)); ?>
	</div>
<?php endforeach; ?>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div>

<?php else: ?>
	<p class="noItems">Раздел не содержит элементов</p>
<?php endif; ?>
 
<div id="catalog_subcategories">
<?php foreach($models as $i=>$model): ?>
	<div id="cf_<?php echo $model->id; ?>" class="catalog_folder">
		<div class="picture">
			<?php echo CHtml::link(CHtml::image($model->getImageUrl('small')),$model->fullUrl);?>
		</div>
		<h2 class="title"><?php echo CHtml::link($model->title,$model->fullUrl);?></h2>
		<div class="description"><?php echo $model->getShortDescription()?></div>
		<div class="itemsCount">наименований(<?php echo $model->itemsCount?>)</div>
		<?php $this->widget('AdminActions',
		array(
			'urlParams'=>array('id'=>$model->id),
			'urlPattern'=>'categories/??',
		)); ?>
	</div>
<?php endforeach;?>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div>
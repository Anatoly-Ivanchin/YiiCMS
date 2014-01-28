<div id="catalog_items">
<?php foreach($models as $n=>$model): ?>
	<div id="ci_<?php echo $model->id ?>" class="catalog_item">
		<div class="picture">
			<?php echo CHtml::link(CHtml::image($model->getSingleImageUrl('small')),$model->fullUrl);?>
		</div>
		<div class="description">
			<h2 class="title"><?php echo CHtml::link(CHtml::encode($model->title),$model->fullUrl); ?></h2>
			<div class="text"><?php echo $model->getAnotation();?></div>
			<div class="params">
			<?php foreach ($model->category->getParams() as $param): ?>
				<span class="catalog_param_name"><?php echo $param->name; ?>:</span>
				<span class="catalog_param_value"><?php echo $model->getParamValue($param,true) ?></span>
			<?php endforeach; ?>
			<?php if($this->getModule()->itemsPrice):?>
				<span class="catalog_price">цена:</span><span class="catalog_price_value"><?php echo $model->price; ?></span>
				<span class="catalog_oldprice">старая цена:</span><span class="catalog_oldprice_value"><?php echo $model->oldPrice; ?></span>
			<?php endif;?>
			<?php if($this->getModule()->itemsCount):?>
				<span class="catalog_count">количество:</span><span class="catalog_count_value"><?php echo $model->count; ?></span>
			<?php endif;?>
			</div>
		</div>
		<?php $this->widget('AdminActions',
		array(
			'urlParams'=>array('id'=>$model->id),
			'urlPattern'=>'items/??',
		)); ?>
	</div>
<?php endforeach; ?>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div>
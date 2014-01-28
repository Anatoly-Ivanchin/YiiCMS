<h1 id="pageTitle"><?php echo $block->title;?></h1>
<div class="news_block_description">
	<?php echo $block->description?>
</div>
<?php //$this->widget('ColoredTagCloud',array('newsBlock'=>$block));?>
<?php $this->widget('SizedTagCloud',array('newsBlock'=>$block));?>
<?php $this->widget('AdminActions',
	array(
		'urlParams'=>array('url'=>$block->url)
	)
); ?>
<?php if($tag):?>
	<div id="news_tag_filter">
	<H2>Записи с меткой "<?php echo $tag->name;?>"</H2>
	<?php echo CHtml::link('Все записи',array('show','url'=>$block->url));?>
	</div>
<?php endif;?>
<div class="news_list">
	<?php foreach ($news as $item):?>
		<div id="news_"<?php echo $item->id;?>>
			<div class="news_header">
				<h2><?php echo CHtml::link($item->title,array('/news/item/show','url'=>$block->url,'id'=>$item->id));?></h2>
				<span class="news_date"><?php echo Yii::app()->dateFormatter->format(Yii::app()->params['dateFormats']['news'], $item->createTime); ?></span>
				<?php echo $item->author->getUserLink();?>
			</div>
			<div class="news_content"><?php echo $item->getAnotation();?></div>
			<?php $this->widget('NewsTags',array('news'=>$item));?>
			<?php if($block->allowComments):?>
				<div id="news_comments_count">
				<?php echo chtml::link("Коментарии (".count($item->comments).")",array('/news/item/show','url'=>$block->url,'id'=>$item->id,'#'=>'news_comments'))?>
				</div>
			<?php endif;?>
			<?php $this->widget('AdminActions',
				array(
					'urlParams'=>array('id'=>$item->id),
					'urlPattern'=>'item/??'
				)
			); ?>
		</div>
	<?php endforeach;?>
	<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div>
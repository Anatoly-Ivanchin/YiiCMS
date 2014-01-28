<?php $nextLevel=$level+1;
      $style='z-index:'.(100+$level).';';
?>

<ul id="<?php echo $this->getMenuId()."_level_".$level; ?>" class="menu_level" style="<?php echo $style ?>">
<?php foreach($items as $key=>$item): 
      $itemId=$this->getMenuId().'_k'.$key?>
<li id="<?php echo $itemId;?>" class="menu_item" >
	<?php
	$linkContent=isset($item['icon'])?
		CHtml::image($item['icon'],$item['title']):
		$item['title']; 
	echo CHtml::link($linkContent,$item['url'],$this->linkHtmlOption($item));
	?>
	<?php if(count($item['children'])>0 && $nextLevel-$this->startLevel<$this->deep) 
		$this->render('menuView',array('items'=>$item['children'],'level'=>$nextLevel)); ?>
</li>
<?php endforeach; ?>
</ul>

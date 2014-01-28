<?php
class SizedTagCloud extends TagCloud
{
	public $maxSize=32;
	public $minSize=8;

	public function getTags()
	{
		$rows=$this->getData();
		if(count($rows)<1)
		    return array();

		$total=0;
		$weights=array();
		foreach($rows as $row)
		{
			$total+=$row['weight'];
			$weights[]=$row['weight'];
		}
		$min_weight = min( $weights );
		$max_weight = max( $weights );
		$spread = $max_weight - $min_weight;
		if ( $spread <= 0 )
		    $spread = 1;
		
		$font_step=($this->maxSize-$this->minSize)/$spread;
		

		$tags=array();
		if($total>0)
		{
			foreach($rows as $row)
			{
				$size=$this->maxSize-($max_weight-$row['weight'])*$font_step;
				$options=array('style'=>'font-size:'.$size.'px;');
				$tags[$row['id']]=CHtml::link(CHtml::encode($row['name']),$this->createLink($row),$options);
			}
			ksort($tags);
		}
		return $tags;
	}
	public function run()
	{
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		
		$averageSize=($this->maxSize-$this->minSize)/2;
		
		$js =<<<EOP
		$("#news_tags_cloud>a").each(function(){
				fontsize=$(this).css("font-size");
				$(this).data("fontsize",fontsize);
				$(this).css("font-size","inherit");
			}
		);
EOP;
		$cs->registerScript('Yii.'.get_class($this).'#1', $js, CClientScript::POS_READY);
		
		$js =<<<EOP
		function animateTag(tag)
		{
			if(!tag.attr('href')) return; 
			fontsize=tag.data("fontsize");
			tag.animate({"font-size":fontsize},function(){animateTag(tag.next())});	
		}
		tag=$("#news_tags_cloud>a:first-child");
		setTimeout(function(){animateTag(tag)},1000);
EOP;

		$cs->registerScript('Yii.'.get_class($this).'#2', $js, CClientScript::POS_LOAD);
		return parent::run();
	}
}
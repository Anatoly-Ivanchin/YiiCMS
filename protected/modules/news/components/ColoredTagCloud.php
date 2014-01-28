<?php
class ColoredTagCloud extends TagCloud
{
	public $maxcolor=array('r'=>0,'g'=>0,'b'=>0);
	public $mincolor=array('r'=>255,'g'=>255,'b'=>255);

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
		
		$color_step=$this->getColorStep($spread);
		

		$tags=array();
		if($total>0)
		{
			$mouseover='this.style.color="rgb('.$this->maxcolor['r'].','.$this->maxcolor['g'].','.$this->maxcolor['b'].')"';
			foreach($rows as $row)
			{
				$red=round($this->maxcolor['r']+($max_weight-$row['weight'])*$color_step['r']);
				$green=round($this->maxcolor['g']+($max_weight-$row['weight'])*$color_step['g']);
				$blue=round($this->maxcolor['b']+($max_weight-$row['weight'])*$color_step['b']);
				$rgb='rgb('.$red.','.$green.','.$blue.')';
				$options=array('style'=>'color:'.$rgb.';','onmouseover'=>$mouseover,'onmouseout'=>'this.style.color="'.$rgb.'"');
				$tags[$row['id']]=CHtml::link(CHtml::encode($row['name']),$this->createLink($row),$options);
			}
			ksort($tags);
		}
		return $tags;
	}
	
	protected function getColorStep($spread)
	{
		$result['r']=($this->mincolor['r']-$this->maxcolor['r'])/$spread;
		$result['g']=($this->mincolor['g']-$this->maxcolor['g'])/$spread;
		$result['b']=($this->mincolor['b']-$this->maxcolor['b'])/$spread;
		return $result;
	}
}
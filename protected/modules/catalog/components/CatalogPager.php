<?php
class CatalogPager extends CLinkPager 
{
	protected function createPageUrl($page)
	{
		$params = $_GET;
		unset($params['cat']);
		
		$pageVar=$this->getPages()->pageVar;
		if($page)
		    $params[$pageVar]=$page+1;
		else
		    unset($params[$pageVar]);
		
		$getParams=array();
		foreach ($params as $k=>$v)
		{
			$getParams[] = $k.'='.$v;
		}
		
		$get = '/'.Yii::app()->getRequest()->getPathInfo();
		if (count($params))
		    $get .= '?';
		$get .= implode('&',$getParams);
		return $get;
	}
}
?>
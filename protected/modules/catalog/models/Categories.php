<?php

class Categories extends CActiveRecord
{
	const IMAGE_PATH='/upload/image/catalog/categories/';
	
	protected $fullUrl = null;
	protected $categoriesChain = null;
	
	protected $allCats=null;
	protected $rootCat=null;
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Catalog_Categories';
	}
	
	public function behaviors()
	{
		return array(
			'metaData'=>'application.components.MetaBehaviors.ModelMetaBehavior',
			'imgBehavior'=>'application.components.behaviors.SinglePicture',
		);
	}
	
	public function rules()
	{
		$rules=array(
		    array('title, picture', 'length', 'max'=>255),
		    array('url', 'length', 'max'=>64),
		    
		    array('url','unique',),
		    array('url','required'),
		    array('url','match','pattern'=>'/^[a-z0-9_-]+$/'),
		    
		    array('description','safe',),
		    
		    array('title','required'),
		    
		    array('priority','numerical','min'=>0,'integerOnly'=>true),
		);
		if($this->mustHaveParent())
		{
		    $rules[]=array('parentId','in','range'=>array_merge(array_keys($this->getAllCategories()),array('')));
		    $rules[]=array('parentId','required');
		}
		return $rules;    
	}
	
	public function mustHaveParent()
	{
		if($this->getIsNewRecord())
			return count($this->getAllCategories())>0;
		else
			return $this->parentId!=null;
	}

	public function relations()
	{
		return array(
			'parent'=>array(self::BELONGS_TO, 'Categories', 'parentId'),
		    'params'=>array(self::HAS_MANY,'Params','categoryId','order'=>'priority'),
		    'children'=>array(self::HAS_MANY,'Categories','parentId'),
		    'childrenCount'=>array(self::STAT,'Categories','parentId'),
		    'items'=>array(self::HAS_MANY,'Items','categoryId'),
		    'itemsCount'=>array(self::STAT,'Items','categoryId'),
		);
	}

	public function attributeLabels()
	{
		return array(
		    'title'=>'Заголовок',
		    'description'=>'Описание',
		    'updateDate'=>'Последний раз обновлено',
		    'parentId'=>'Родительский раздел',
		    'priority'=>'Приоритет',
	    );
	}
	
	public function pictures()
	{
		return array(
		    'small'=>array(
		        'w'=>180,
		        'h'=>120,
		        'path'=>self::IMAGE_PATH.'small/',
		        'noimg'=>'/images/no_pic.gif',
		    ),
		    'full'=>array(
		        'path'=>self::IMAGE_PATH.'full/',
		        'noimg'=>'/images/no_big_pic.gif',
		    ),
		);
	}

	protected function beforeValidate()
	{
		$this->updateDate=new CDbExpression('now()');
		return parent::beforeValidate();
	}
	
	public function getAllCategories()
	{
		if($this->allCats==null)
		{
			$criteria = new CDbCriteria();
			if (isset($this->id))
			    $criteria->condition='id<>'.$this->id;
			$result=array();
			foreach($this->model()->findAll($criteria) as $cat)
			    $result[$cat->id]=$cat->title;
			$this->allCats=$result;
		}
		return $this->allCats;
	}
	
	public function getRootCategory()
	{
		if($this->rootCat==null)
		{
			$criteria = new CDbCriteria();
			$criteria->condition='isnull(parentId)';
			$result=array();
			$this->rootCat=$this->find($criteria);
		}
		return $this->rootCat;
	}
	
	public function getParams()
	{
		if (isset($this->parentId))
		    return array_merge($this->parent->getParams(),$this->params);
		else
		    return $this->params;
	}
	
	public function getSubCategories($includeThis=true,$cat=null)
	{
		$result=array();
		if($cat==null)
		    $cat=$this;
		if($includeThis)
		    $result[]=$this;
		$result=array_merge($result,$cat->children);
		foreach ($cat->children as $child)
		{
			$result=array_merge($result,$this->getSubCategories(false,$child));
		}
		return $result;
	}
	
	public function getCategoriesChain($withSelf = true)
	{
		if($this->categoriesChain ==null)
		{
			$arr = array();
			if($this->parent!=null)
			    $arr=$this->parent->getCategoriesChain();
			if ($withSelf)
		        $arr[]=$this;
		    $this->categoriesChain = $arr;
		}
		return $this->categoriesChain;
	}
	
	public function getFullUrl()
	{
		if ($this->fullUrl==null)
		{
			$url='';
			if($this->parent!=null)
		        $url=$this->parent->getFullUrl();
		    if($this->url!=null)
		        $url.='/'.$this->url;
		    $this->fullUrl = $url;
		}
		return $this->fullUrl;
	}
	
	protected function afterDelete()
	{
		Params::model()->deleteAllByAttributes(array('categoryId'=>$this->id));
		foreach ($this->items as $item)
		    $item->delete();
		foreach ($this->children as $child)
		    $child->delete();		    
	}
	
	public function  getShortDescription($lenght=255)
	{
		if($this->description!=null)
		{
		    $text=strip_tags($this->description);
		    return substr($text,0,$lenght).'...';
		}
		return null;
	}
}
<?php

class Items extends CActiveRecord
{
	const IMAGES_RELATION='pictures';
	
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
		return 'Catalog_Items';
	}
	
	public function behaviors()
	{
		return array(
			'metaData'=>'application.components.MetaBehaviors.ModelMetaBehavior',
			'imgBehavior'=>array(
				'class'=>'application.components.behaviors.MultiPictures',
				'imagesRelationName'=>self::IMAGES_RELATION,
				'imgAttr'=>'filePath',
			)
		);
	}
	
	public function rules()
	{
		return array(
		    array('title, shortDescription', 'length', 'max'=>255),
		    array('url', 'length', 'max'=>64),
		    
		    array('url','unique',),
		    array('url','required'),
		    array('url','match','pattern'=>'/^[a-z0-9_-]+$/','message'=>'url должен содержать только латинские буквы в нижнем регистре'),
		    	    
		    array('title','required'),
		    
		    array('marked','boolean'),
		    
		    array('categoryId','in','range'=>array_keys($this->getAllCategories()),'message'=>'Указан не верный раздел каталога'),
		    
		    array('priority','numerical','min'=>0,'integerOnly'=>true),
		    
		    array('price,oldPrice','numerical','min'=>0),
		    array('count','numerical','min'=>0,'integerOnly'=>true),
		    
		    array('description','safe'),
		);
	}

	public function relations()
	{
		return array(
			'category'=>array(self::BELONGS_TO, 'Categories', 'categoryId'),
		    'params'=>array(self::HAS_MANY, 'Values', 'itemId'),
			self::IMAGES_RELATION=>array(self::HAS_MANY, 'Images', 'itemId'),
		);
	}

	public function attributeLabels()
	{
		return array(
		    'title'=>'Наименование',
		    'shortDescription'=>'Краткое описание',
		    'description'=>'Описание',
		    'updateDate'=>'Последний раз обновлено',
		    'categoryId'=>'Раздел',
		    'price'=>'Цена',
		    'oldPrice'=>'Старая цена',
		    'count'=>'В наличии',
		    'picture'=>'Изображение',
		    'marked'=>'Отметить',
		    'priority'=>'Приоритет'
	    );
	}

	protected function beforeValidate()
	{
		$this->updateDate=new CDbExpression('now()');
		return parent::beforeValidate();
	}
	
	protected function afterSave()
	{
		if(!isset($_POST['params']))
			return parent::afterSave();

		$delete=array();
		foreach ($this->category->getParams() as $param)
		{
			$add=array();

			if(!isset($_POST['params'][$param->id]))
				continue;
			$values=$_POST['params'][$param->id];
			if($param->inputType==Params::INPUT_TYPE_LIST)
			{
				if(is_array($values['add']))
					foreach ($values['add'] as $str)
						$add[]=$str;
				if(is_array($values['delete']))
					foreach ($values['delete'] as $id=>$fake)
						$delete[]=$id;
			}
			else
			{
				$val=$this->getParamValue($param,false,true);
				if(count($val)>0)
				{
					if($val[0]->value!==$values)
					{
						$val[0]->value=$values;
						$val[0]->save();
					}
				}
				else 
					$add[]=$values;
			}
		
			foreach ($add as $value)
			{
				$newVal=new Values();
				$newVal->itemId=$this->id;
				$newVal->paramId=$param->id;
				$newVal->value=$value;
				$newVal->save();
			}
		}
		Values::model()->deleteByPk($delete);
		parent::afterSave();
	}
	
	protected function afterDelete()
	{
		Values::model()->deleteAllByAttributes(array('itemId'=>$this->id));
		parent::afterDelete();
	}
	
	public function getAllCategories()
	{
		$result=array();
		foreach(Categories::model()->findAll() as $cat)
		    $result[$cat->id]=$cat->title;
		return  $result;
	}
	
	public function hasParamValues()
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('itemId'=>$this->id));
		
		return Values::model()->count($criteria)>0;
	}
	
	public function getParamValue($param,$withUnit = false,$returnModel=false)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('itemId'=>$this->id,'paramId'=>$param->id));
		
		$values =  Values::model()->findAll($criteria);
		
		if($returnModel)
			return $values;
		
		switch ($param->inputType)
		{
			case Params::INPUT_TYPE_VALUE:
				$value=$values[0]->value;
				break;
			case Params::INPUT_TYPE_CHECK:
				$value=$values[0]->value?'Да':'Нет';
				break;
			case Params::INPUT_TYPE_LIST:
				$value=array();
				foreach ($values as $v)
					$value[]=$v->value;
				$value=implode(', ', $value);
				break;
		}
		
		if ($withUnit)
		{
			return $value?$value.' '.$param->unit:'не указанно';
		}
		else
		    return $value;
	}

	public function getAnotation($lenght=255)
	{
		if($this->shortDescription!=null)
		    return $this->shortDescription;
		if($this->description!=null)
		{
		    $text=strip_tags($this->description);
		    return substr($text,0,$lenght).'...';
		}
		return '...';
	}
	
	public function getFullDescription()
	{
		if($this->description!=null)
		    return $this->description;
		if($this->shortDescription!=null)
		    return $this->shortDescription;
		return '...';
	}
	
	public function getFullUrl()
	{
		$url='';
		if($this->category!=null)
		    $url=$this->category->getFullUrl();
		if($this->url!=null)
		    $url.='/'.$this->url;
		return $url;
	}
}
<?php

class MenuItems extends CActiveRecord
{
	const TYPE_MODULE_PAGE=0;
	const TYPE_STATIC=1;
	const TYPE_GROUP=2;
	
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
		return 'Menu_Items';
	}
	
	public function behaviors()
	{
		return array(
			'imgBehavior'=>array(
				'class'=>'application.components.behaviors.SinglePicture',
				'imgAttr'=>'icon',
			),
		);
	}
	
	public function rules()
	{
		return array(
		    array('title,type','required'),
		    array('title,url,page,pattern','length','max'=>128),
		    array('module','length','max'=>64),
		    array('description','length','max'=>255),
		    array('withChildren,inNewWindow','boolean'),
		    array('menuId','exist','className'=>'Menu','attributeName'=>'ident','allowEmpty'=>false,'on'=>'insert'),
		    array('parentId','exist','attributeName'=>'id','criteria'=>array('condition'=>'menuId=:mid','params'=>array(':mid'=>$_GET['ident'])),'on'=>'insert'),
		    array('type','in','range'=>array_keys($this->getTypeList()),'allowEmpty'=>false),
		);
	}
	
	public function relations()
	{
		return array(
			'parent'=>array(self::BELONGS_TO, 'MenuItems', 'parentId'),
			'children'=>array(self::HAS_MANY, 'MenuItems', 'parentId'),
			'childrenCount'=>array(self::STAT, 'MenuItems', 'parentId'),
			'menu'=>array(self::BELONGS_TO, 'Menu', 'menuId'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
		    'menuId'=>'Меню',
		    'title'=>'Заголовок',
		    'description'=>'Подсказка',
		    'icon'=>'Иконка',
		    'priority'=>'Приоритет',
		    'parentId'=>'Родительский элемент',
		    'module'=>'Модуль',
		    'page'=>'Страница',
		    'withChildren'=>'Включая вложенные страницы',
		    'type'=>'Тип',
		    'updateTime'=>'Последний раз обновлено',
		    'pattern'=>'Шаблон',
		    'inNewWindow'=>'Открыть в новом окне'
	    );
	}

	protected function beforeValidate()
	{
		$this->updateTime=new CDbExpression('now()');
		return parent::beforeValidate();
	}
	
	protected function afterDelete()
	{
		foreach ($this->children as $child)
			$child->delete();
		return parent::afterDelete();
	}
	
	public function getTypeList()
	{
		return array(
			self::TYPE_MODULE_PAGE=>'Страница модуля',
			self::TYPE_STATIC=>'Статичный url',
			self::TYPE_GROUP=>'Группа',
		);
	}
	
	public function canHasSubMenu()
	{
		return $this->type!=self::TYPE_MODULE_PAGE || !$this->withChildren;
	}
	
	public function displayUrl()
	{
		switch ($this->type){
			case self::TYPE_MODULE_PAGE:
				return $this->module.'/'.$this->page;
			case self::TYPE_STATIC: 
				return $this->url;
			default:
				return "";
		}
	}
}
<?php

class Menu extends CActiveRecord
{
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
		return 'Menu';
	}
	
	public function behaviors()
	{
		return array(
		);
	}
	
	public function rules()
	{
		return array(
		    array('ident','unique'),
		    array('ident','required'),
		    array('ident','length','max'=>4),
		    
		    array('description','required'),
		    array('description','length','max'=>64),
		);
	}

	
	public function attributeLabels()
	{
		return array(
		    'ident'=>'Идентификатор',
		    'description'=>'Описание',
	    );
	}
	
	public function relations()
	{
		return array(
			'items'=>array(self::HAS_MANY, 'MenuItems', 'menuId'),
			'itemsOnlyTopLevel'=>array(self::HAS_MANY, 'MenuItems', 'menuId', 'condition'=>'parentId IS NULL'),
		);
	}
	
	protected function afterDelete()
	{
		foreach ($this->itemsOnlyTopLevel as $item)
			$item->delete();
		return parent::afterDelete();
	}
}
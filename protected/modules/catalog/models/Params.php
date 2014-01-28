<?php

class Params extends CActiveRecord
{
	const INPUT_TYPE_VALUE=1;
	const INPUT_TYPE_LIST=2;
	const INPUT_TYPE_CHECK=3;
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
		return 'Catalog_Params';
	}
	
	public function rules()
	{
		return array(
		    array('name,unit', 'length', 'max'=>255),
		    array('name','required'),
		    array('inputType','in','range'=>array_keys($this->getInputTypes()),'allowEmpty'=>false),
		);
	}

	public function relations()
	{
		return array(
			'category'=>array(self::BELONGS_TO, 'Categories', 'categoryId'),
		);
	}

	public function attributeLabels()
	{
		return array(
		    'name'=>'Имя',
		    'unit'=>'Единица измерения',
		    'updateDate'=>'Последний раз обновлено',
		    'categoryId'=>'Категория',
		    'priority'=>'Приоритет',
		    'inputType'=>'Тип ввода'
	    );
	}

	protected function beforeValidate()
	{
		$this->updateDate=new CDbExpression('now()');
		return parent::beforeValidate();
	}
	
	public function getInputTypes()
	{
		return array(
			self::INPUT_TYPE_VALUE=>'Значение',
			self::INPUT_TYPE_LIST=>'Список значений',
			self::INPUT_TYPE_CHECK=>'Да/Нет',
		);
	}
	
	public function getAllCategories()
	{
		$result=array();
		foreach($this->model()->findAll($criteria) as $cat)
		    $result[$cat->id]=$cat->title;
		return  $result;
	}
	
	public function getValues($id)
	{
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('itemId'=>$id,'paramId'=>$this->id));
		
		return Values::model()->findAll($criteria);
	}
}
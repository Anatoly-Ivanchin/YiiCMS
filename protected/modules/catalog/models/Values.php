<?php

class Values extends CActiveRecord
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
		return 'Catalog_Items_Params';
	}
	
	public function rules()
	{
		return array(
		    array('value','length', 'max'=>255),
		);
	}

	public function relations()
	{
		return array(
			'param'=>array(self::BELONGS_TO, 'Params', 'paramId'),
			'item'=>array(self::BELONGS_TO, 'Items', 'itemId'),
		);
	}

	public function attributeLabels()
	{
		return array(
		    'value'=>'Значение',
	    );
	}
	
	public function safeAttributes()
	{
		return array(
			'value',
		);
	}

	protected function beforeValidate()
	{
		$this->updateDate=new CDbExpression('now()');
		return parent::beforeValidate();
	}
	
	public function getAllCategories()
	{
		$criteria = new CDbCriteria();
		if (isset($this->id))
		    $criteria->condition='id<>'.$this->id;
		$result=array();
		foreach($this->model()->findAll($criteria) as $cat)
		    $result[$cat->id]=$cat->title;
		return  $result;
	}
}
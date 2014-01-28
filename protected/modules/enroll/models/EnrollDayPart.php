<?php
	
class EnrollDayPart extends CActiveRecord
{
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Enroll_DayPart';
	}
	

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
		    array('name','length','max'=>128),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => 'Название',
		);
	}
	
	public function relations()
	{
		return array(
			'enroll'=>array(self::HAS_MANY,'Enroll','daypartId')
		);	
	}
}
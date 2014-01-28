<?php
	
class LessonSchools extends CActiveRecord
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
		return 'Lesson_Schools';
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
			'name' => 'Наименование',
		);
	}
	
	public function relations()
	{
		return array(
			'schedule'=>array(self::HAS_MANY,'LessonSchedule','schoolId')
		);	
	}
	
	public function afterDelete()
	{
		foreach ($this->schedule as $item) {
			$item->delete();
		}
		return parent::afterDelete();
	}
}
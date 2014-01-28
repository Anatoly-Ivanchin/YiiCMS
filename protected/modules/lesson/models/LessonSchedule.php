<?php
	
class LessonSchedule extends CActiveRecord
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
		return 'Lesson_Schedule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
		    array('eventDate','type','type'=>'datetime','datetimeFormat'=>'yyyy-MM-dd hh:mm:ss','allowEmpty'=>false),
		    array('vacancies','numerical','min'=>1,'integerOnly'=>true,'allowEmpty'=>false),
		    array('programmId','in','range'=>array_keys($this->getProgrammsList()),'allowEmpty'=>false),
		    array('schoolId','in','range'=>array_keys($this->getSchoolsList()),'allowEmpty'=>false),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'programmId' =>'Программа',
			'schoolId' =>'Школа',
			'vacancies'=>'Количество мест',
			'eventDate'=>'Дата проведения'
		);
	}
	
	public function relations()
	{
		return array(
			'programm'=>array(self::BELONGS_TO,'EnrollProgramms','programmId'),
			'school'=>array(self::BELONGS_TO,'LessonSchools','schoolId'),
			'enrolls'=>array(self::HAS_MANY,'Lesson','scheduleId'),
			'enrollsCount'=>array(self::STAT,'Lesson','scheduleId')
		);	
	}
	
	public function afterDelete()
	{
		foreach ($this->enrolls as $item) {
			$item->delete();
		}
		return parent::afterDelete();
	}
	
	public function getProgrammsList()
	{
		$arr=array();
		foreach (EnrollProgramms::model()->findAll() as $lang) {
			$arr[$lang->id]=$lang->name;			
		}
		return $arr;
	}

	public function getSchoolsList()
	{
		$arr=array();
		foreach (LessonSchools::model()->findAll() as $lang) {
			$arr[$lang->id]=$lang->name;			
		}
		return $arr;
	}
}
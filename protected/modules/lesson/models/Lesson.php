<?php
	
class Lesson extends CActiveRecord
{
	public $captcha;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Lesson';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
		    array('name,middleName,lastName,email','length','max'=>128),
		    array('phone','length','max'=>12),
		    array('name,lastName,phone,email,scheduleId','required'),
		    array('email','email'),
		    array('scheduleId','in','range'=>array_keys($this->getScheduleList())),
		    array('adId','in','range'=>array_keys($this->getAdsList())),
		    array('captcha','captcha','allowEmpty'=>!Yii::app()->user->isGuest),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => 'Имя',
			'middleName' => 'Отчество',
			'lastName' => 'Фамилия',
			'fullname'=>'Ф.и.о.',
			'phone' => 'Телефон',
			'email' => 'Адрес электронной почты',
			'scheduleId'=>'Расписание занятий',
			'adId'=>'&mdash; Как Вы о нас узнали?',
			'createTime'=>'Дата',
			'captcha'=>'Код проверки',
		);
	}
	
	public function relations()
	{
		return array(
			'schedule'=>array(self::BELONGS_TO,'LessonSchedule','scheduleId'),
			'ad'=>array(self::BELONGS_TO,'EnrollAds','adId'),
		);	
	}

	public function beforeValidate(){
		$this->createTime=new CDbExpression('NOW()');
		return parent::beforeValidate();
	}
	
	public function getScheduleList()
	{
		$arr=array();
		foreach (LessonSchedule::model()->findAll() as $item) {
			$arr[$item->id]=$item->eventDate;
		}
		return $arr;
	}
	
	public function getAdsList()
	{
		$arr=array();
		foreach (EnrollAds::model()->findAll() as $lang) {
			$arr[$lang->id]=$lang->name;			
		}
		return $arr;
	}
	
	public function getFullName()
	{
		$str=$this->lastName.' '.mb_substr($this->name,0,1,'utf-8').'.';
		if($this->middleName!=null)
			$str.=' '.mb_substr($this->middleName, 0,1,'utf-8').'.';
		return $str;
	}
}
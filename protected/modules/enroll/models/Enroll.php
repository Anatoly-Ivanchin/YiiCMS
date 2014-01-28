<?php
	
class Enroll extends CActiveRecord
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
		return 'Enroll';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
		    array('name,middleName,lastName,email','length','max'=>128),
		    array('phone','length','max'=>12),
		    array('name,lastName,phone,email,programmId,daypartId,schoolId','required'),
		    array('email','email'),
		    array('programmId','in','range'=>array_keys($this->getProgrammList())),
		    array('adId','in','range'=>array_keys($this->getAdsList())),
		    array('daypartId','in','range'=>array_keys($this->getDayPartsList())),
		    array('captcha','captcha','allowEmpty'=>!Yii::app()->user->isGuest),
		    array('schoolId','in','range'=>array_keys($this->getSchoolsList()),'allowEmpty'=>false),
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
			'programmId'=>'Программа',
			'daypartId'=>'Удобное для Вас время',
			'adId'=>'&mdash; Как Вы о нас узнали?',
			'captcha'=>'Код проверки',
			'createTime'=>'Дата',
			'schoolId' =>'Филиал',
		);
	}
	
	public function relations()
	{
		return array(
			'programm'=>array(self::BELONGS_TO,'EnrollProgramms','programmId'),
			'daypart'=>array(self::BELONGS_TO,'EnrollDayPart','daypartId'),
			'ad'=>array(self::BELONGS_TO,'EnrollAds','adId'),
			'school'=>array(self::BELONGS_TO,'LessonSchools','schoolId'),
		);	
	}
	
	public function beforeValidate(){
		$this->createTime=new CDbExpression('NOW()');
		return parent::beforeValidate();
	}
	
	public function getProgrammList()
	{
		$arr=array();
		foreach (EnrollProgramms::model()->findAll() as $lang) {
			$arr[$lang->id]=$lang->name;			
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

	public function getDayPartsList()
	{
		$arr=array();
		foreach (EnrollDayPart::model()->findAll() as $lang) {
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
	
	public function getSchoolsList()
	{
		$arr=array();
		foreach (LessonSchools::model()->findAll() as $lang) {
			$arr[$lang->id]=$lang->name;			
		}
		return $arr;
	}
}
<?php

class Meta extends CActiveRecord
{
	/**
	 * 
	 * Enter description here ...
	 * @param $className
	 * @return CActiveRecord
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
		return 'Meta';
	}
	
	public function attributeLabels()
	{
		return array(
			'title'=>'Заголовок',
			'tags'=>'Теги',
			'description'=>'Описание'
		);
	}
	
	public function safeAttributes()
	{
		return array(
			'tags',
			'title',
			'description',
		);
	}
	
	public function rules()
	{
		return array(
			array('title, tags, description', 'length', 'max'=>512),
		);
	}
}
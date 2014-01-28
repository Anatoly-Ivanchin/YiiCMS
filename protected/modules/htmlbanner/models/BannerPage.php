<?php
	
class BannerPage extends CActiveRecord
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
		return 'Html_Banner';
	}
	
	public function behaviors()
	{
		return array(
			'imgBehavior'=>array(
				'class'=>'application.components.behaviors.SinglePicture',
			),
		);
	}
	

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
		    array('title','length','max'=>128),
			array('title','required'),
			
			array('description','length','max'=>512),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'title' => 'Заголовок',
			'description' => 'Содержание',
		);
	}
}
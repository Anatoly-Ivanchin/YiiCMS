<?php

class Block extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'Block':
	 * @var string $ident
	 * @var string $title
	 * @var string $content
	 * @var string $update_date
	 */

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
		return 'Block';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('ident','length','max'=>128),
			array('ident','required','message'=>'Идентификатор не заполнен.'),
			array('ident','match','pattern'=>'/^[a-z0-9_]+$/','message'=>'Идентификатор должен содержать только латинские буквы в нижнем регистре.'),
			
			array('title','length','max'=>255),
			array('title','required','message'=>'Заголовок не заполнен.'),
			
			array('content','safe'),
			array('displayTitle','boolean')
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ident' => 'Идентификатор',
			'title' => 'Заголовок',
			'content' => 'Содержание',
			'update_date' => 'Дата последнего обновления',
			'displayTitle'=>'Отображать заголовок'
		);
	}
	
	protected function beforeValidate()
	{
		$this->update_date=new CDbExpression('now()');
		return parent::beforeValidate();
	}
}
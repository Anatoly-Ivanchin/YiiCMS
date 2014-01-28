<?php

class Page extends CActiveRecord
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
		return 'Page';
	}
	
	public function behaviors()
	{
		return array(
			'metaData'=>'application.components.MetaBehaviors.ModelMetaBehavior',
		);
	}
	
	public function rules()
	{
		return array(
		    array('title, url', 'length', 'max'=>255),
		    
		    array('url','unique','allowEmpty'=>false),
		    array('url','match','pattern'=>'/^[a-z0-9_\-]*$/'),
		    
		    array('title','required'),
		    
		    array('content','required'),
		);
	}

	
	public function attributeLabels()
	{
		return array(
		    'title'=>'Заголовок',
		    'content'=>'Содержание',
		    'updateTime'=>'Последний раз обновлено',
	    );
	}
	
	public function safeAttributes()
	{
		return array(
			'title',
		    'url',
			'content',
		);
	}

	protected function beforeValidate()
	{
		$this->updateTime=new CDbExpression('now()');
		return true;
	}
}
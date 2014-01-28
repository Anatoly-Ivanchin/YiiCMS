<?php

class Vote extends CActiveRecord
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
		return 'Vote_Log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		    'answer' => array(self::HAS_ONE, 'Answer', 'answerId'),
		);
	}	
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
		    'ip' => 'ip пользователя',
		);
	}
	
    protected function beforeValidate($on)
    {
        if (parent::beforeValidate($on)) {
            if($this->getIsNewRecord())
        	    $this->ip = Yii::app()->getRequest()->getUserHostAddress();
            return true;
        }
        return false;
    }
}
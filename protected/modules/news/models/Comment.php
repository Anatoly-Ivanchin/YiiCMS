<?php
	
class Comment extends CActiveRecord
{
	public $captcha;
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
		return 'News_Comments';
	}
	

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$rules=array(
		    array('name','length','max'=>128),
			array('text','length','max'=>512,'min'=>2, 'allowEmpty'=>false),
		);
		if(Yii::app()->user->getIsGuest())
		{
			$rules[]=array('name','required');
			$rules[]=array('captcha','captcha');
		}
		return $rules;		
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		    'author'=>array(self::BELONGS_TO,'User','userId'),
		    'news'=>array(self::BELONGS_TO,'News','newsId'),		
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => 'Ваше имя',
			'text' => 'Текст комментария',
			'captcha'=>'Код проверки'
		);
	}
    
    protected function beforeValidate()
    {
        $this->updateTime=new CDbExpression('now()');
        if(!Yii::app()->user->getIsGuest())
            $this->userId=Yii::app()->user->id;
    	    
        return parent::beforeValidate();
    }
    
    public function getUserName()
    {
    	if(!$this->author)
	    	return $this->name;
    	else
	    	return $this->author->username;
    }
}
<?php

class FaqBlock extends CActiveRecord
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
		return 'Faq_Block';
	}
	
	public function behaviors()
	{
		return array(
			'metaData'=>'application.components.MetaBehaviors.ModelMetaBehavior',
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('url','length','max'=>128),
			array('url','unique','on'=>array('insert')),
		    array('url','required'),
		    array('url','match','pattern'=>'/^[a-z0-9_\-]+$/'),
		
			array('title','length','max'=>128),
			array('title','required'),
			
			array('autoPublish','boolean'),
			array('notifyUser','boolean'),
			
			array('description','safe'),
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
		    'items'=>array(self::HAS_MANY,'FaqItems','blockId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'title' => 'Заголовок',
			'updateTime' => 'Дата обновления',
		    'description'=>'Краткое описание',
		    'lastRevisionTime'=>'Время последнего просмотра',
		    'autoPublish'=>'Публиковать сообщения пользователей автоматически',
		    'notifyUser'=>'Уведомлять пользователей',
		);
	}
    
    protected function beforeValidate()
    {
        $this->updateTime=new CDbExpression('now()');
        return parent::beforeValidate();
    }
    
    protected function afterDelete()
    {
    	foreach ($this->items as $item)
    	    $item->delete();
    }
}
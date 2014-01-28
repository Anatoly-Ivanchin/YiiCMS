<?php

class NewsBlock extends CActiveRecord
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
		return 'News_Block';
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
			
			array('allowComments,allowTags,isGallery','boolean'),
			
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
		    'news'=>array(self::HAS_MANY,'News','newsBlockId'),
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
		    'allowComments'=>'Разрешить комментарии',
		    'allowTags'=>'Использовать тэги',
		    'isGallery'=>'Отображать как фотогаллерею'
		);
	}
    
    protected function beforeValidate()
    {
        $this->updateTime=new CDbExpression('now()');
        return parent::beforeValidate();
    }
    
    protected function afterDelete()
    {
    	foreach ($this->news as $news)
    	    $news->delete();
    }
}
<?php

class Tag extends CActiveRecord
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
		return 'Tag';
	}
	
	public function relations()
	{
		return array(
		    'news'=>array(self::MANY_MANY, 'News', 'News_Tag(tagId, newsId)',
				'together'=>false,
				'joinType'=>'LEFT JOIN'),
		);
	}
}
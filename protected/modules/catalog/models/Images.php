<?php

class Images extends CActiveRecord
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
		return 'Catalog_Images';
	}

	public function relations()
	{
		return array(
			'item'=>array(self::BELONGS_TO, 'Items', 'itemId'),
		);
	}
}
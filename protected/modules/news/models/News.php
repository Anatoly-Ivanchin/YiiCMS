<?php
	
class News extends CActiveRecord
{
	
	protected $tagStr='';
	
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
		return 'News';
	}
	
	public function behaviors()
	{
		return array(
			'metaData'=>'application.components.MetaBehaviors.ModelMetaBehavior',
			'imgBehavior'=>array(
				'class'=>'application.components.behaviors.SinglePicture',
				'settingsPath'=>'getSettingPath',
			),
		);
	}
	

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$rules = array(
		    array('title','length','max'=>128),
			array('title','required'),
			
			array('description','length','max'=>255),
			array('published', 'boolean'),
			
			array('tagStr', 'match', 'pattern'=>'/^[\S+\s*,\s*]*\S+$/'),
			
			array('content',!$this->newsblock->isGallery?'required':'safe'),
		);
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
		    'author'=>array(self::BELONGS_TO,'User','authorId'),
		    'newsblock'=>array(self::BELONGS_TO,'NewsBLock','newsBlockId'),
		    'comments'=>array(self::HAS_MANY,'Comment','newsId'),
		    'tags'=>array(self::MANY_MANY, 'Tag', 'News_Tag(newsId, tagId)',
				'together'=>false,
				'joinType'=>'LEFT JOIN'),
			'tagFilter'=>array(self::MANY_MANY, 'Tag', 'News_Tag(newsId, tagId)',
				'together'=>true,
				'joinType'=>'INNER JOIN',
				'condition'=>'tagFilter.id=:tag'),		
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'title' => 'Заголовок',
			'content' => 'Содержание',
			'published' => 'Опубликованно',
			'createTime' => 'Дата создания',
			'updateTime' => 'Дата обновления',
			'authorId'=>'Автор',
		    'description'=>'Краткое описание',
		    'newsBlockId'=>'Новостной блок',
		    'picture'=>'Изображение',
		    'tagStr'=>'Теги',
		);
	}
	
    public function safeAttributes()
    {
        return array('title', 'description', 'content', 'status', 'published','newsBlockId');
    }
    
    protected function beforeValidate()
    {
        if($this->isNewRecord)
        {
            $this->createTime=$this->updateTime=time();
            $this->authorId=Yii::app()->user->id;
        }
        $this->updateTime=time();
    	    
        return parent::beforeValidate();
    }

    protected function afterSave()
	{
		if($this->newsblock->allowTags)
		{
			if(!$this->isNewRecord)
				$this->deleteTags();

			foreach($this->getTagArray() as $name)
			{
				$tag=Tag::model()->findByAttributes(array('name' =>$name));
				if($tag===null)
				{
					$tag=new Tag();
					$tag->name=$name;
					$tag->save();
				}
				$this->dbConnection->createCommand("INSERT INTO News_Tag (newsId, tagId) VALUES ({$this->id},{$tag->id})")->execute();
			}
		}
		return parent::afterSave();
	}

	/**
	 * Postprocessing after the record is deleted
	 */
	protected function afterDelete()
	{
		if($this->newsblock->allowTags)
			$this->deleteTags();
		if($this->newsblock->allowComments)
			foreach ($this->comments as $comment)
				$comment->delete();
		return parent::afterDelete();
	}
    
    public function getAnotation($lenght=255)
    {
    	if($this->description!=null)
		    return $this->description;
		if($this->content!=null)
		{
		    $text=strip_tags($this->content);
		    return substr($text,0,$lenght).'...';
		}
		return '';    	    
    }
    
    public function getBlocksList()
    {
    	$blocks = array();
    	foreach (NewsBlock::model()->findAll() as $block)
    	{
    		$blocks[$block->url]=$block->title;
    	}
    	return $blocks;
    }
	
	// TAGS
	
	public function getTagStr()
	{
		if($this->tagStr==null)
		{
			$tags=array();
			foreach($this->tags as $t)
		    $tags[]=$t->name;
		    $this->tagStr=implode(', ',$tags);
		}
		return $this->tagStr;
	}

	public function setTagStr($value)
	{
		$this->tagStr=$value;
	}
	
	protected  function getTagArray()
	{
		return array_unique(preg_split('/\s*,\s*/',trim($this->tagStr),-1,PREG_SPLIT_NO_EMPTY));
	}
	
	protected function deleteTags()
	{
		foreach($this->tags as $t)
		{
			if (count($t->news)<2)
			    $t->delete();
		}
		$this->dbConnection->createCommand('DELETE FROM News_Tag WHERE newsId='.$this->id)->execute();
	}
	
	public function getSettingPath()
	{
		return $this->newsblock->isGallery?'Gallery':get_class($this);
	}
}
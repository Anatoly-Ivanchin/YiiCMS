<?php
$path='/upload/image/';
return array(
	'MenuItems'=>array(
			'preview'=>array(
					'w'=>16,
					'h'=>16,
					'path'=>$path.'menu/previews/',
			),
	),
	'BannerPage'=>array(
			'preview'=>array(
					'w'=>100,
					'h'=>150,
					'path'=>$path.'banner/previews/',
			),
			'full'=>array(
					'w'=>1536,
					'h'=>750,
					'path'=>$path.'banner/full/',
			),
	),
	'News'=>array(
	    'preview'=>array(
	        'w'=>100,
	        'h'=>150,
	        'path'=>$path.'news/previews/',
	    ),
	    'small'=>array(
	        'w'=>270,
	        'path'=>$path.'news/small/',
	    ),
	    'full'=>array(
		    'w'=>797,
	        'path'=>$path.'news/full/',
	        'noimg'=>'/images/no_big_pic.gif',
	    ),
	),
	'Gallery'=>array(
	    'preview'=>array(
	        'w'=>100,
	        'h'=>150,
	        'path'=>$path.'gallery/previews/',
	    ),
	    'small'=>array(
	        'w'=>270,
	        'path'=>$path.'gallery/small/',
	        'noimg'=>Yii::app()->theme->baseUrl.'/img/catalog_no_img.jpg',
	    ),
	    'full'=>array(
		    'w'=>797,
	        'path'=>$path.'gallery/full/',
	        'noimg'=>'/images/no_big_pic.gif',
	    ),
	),
	'Categories'=>array(
	    'preview'=>array(
	        'w'=>70,
	        'h'=>105,
	        'path'=>$path.'catalog/categories/preview/',
	        'noimg'=>Yii::app()->theme->baseUrl.'/img/catalog_no_img.jpg',
	    ),
	    'small'=>array(
	        'w'=>203,
	        'h'=>271,
	        'path'=>$path.'catalog/categories/small/',
	        'noimg'=>Yii::app()->theme->baseUrl.'/img/catalog_no_img.jpg',
	    ),
	),
	'Items'=>array(
	    'preview'=>array(
	        'w'=>70,
	        'h'=>70,
	        'path'=>$path.'catalog/items/preview/',
	        'noimg'=>Yii::app()->theme->baseUrl.'/img/catalog_no_img.jpg',
	    ),
	    'small'=>array(
	        'w'=>192,
	        'h'=>192,
	        'path'=>$path.'catalog/items/small/',
	        'noimg'=>Yii::app()->theme->baseUrl.'/img/no_foto.gif',
	    ),
	    'medium'=>array(
	        'w'=>360,
	        'h'=>360,
	        'path'=>$path.'catalog/items/medium/',
	        'noimg'=>Yii::app()->theme->baseUrl.'/img/no_big_foto.gif',
	    ),
	    'full'=>array(
	        'path'=>$path.'catalog/items/full/',
	    ),
	)
);
<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'charset'=>'utf-8',
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Жемчуг',
    'language'=>'ru',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	// application components
	'components'=>array(
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'info, error, warning',
				),
			),
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'cache'=>array(
		    'class'=>'system.caching.CDummyCache',
		),		
		'db'=>include 'db.php',
		'urlManager'=>array(
		    'class'=>'UrlManager',
			'urlFormat'=>'path',
		    'showScriptName'=>false,
		    'rules'=>array(
		        '/'=>'site/index',
		    ),
		
		),
		'widgetFactory'=>include 'widgets.php',
		'errorHandler'=>array(
            'errorAction'=>'site/error',
        ),
	),

	'params'=>include 'params.php',
	
	'modules'=>array(
	    'users',
	    'htmlbanner',
	    'catalog'=>array(
	        'itemsPrice'=>true,
	        'itemsCount'=>true,
	        'cssFile'=>null
	    ),
	    'page',
	    'news'=>array(
		    'galleryMode'=>true,
	    ),
	    'blocks',
	    'vote',
	    'faq',
	    'menu',
	),
	
	'theme'=>'pearl',
	
	
);
<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

// USEFUL:
// for installing yii-user http://www.benjaminlhaas.com/blog/installing-yii-users-and-rights-5-steps
// for 'auth' module https://github.com/Crisu83/yii-auth


$config_array = array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Prize Manager',

	// preloading 'log' component
	'preload'=>array('log','input'),

     // path aliases
    'aliases' => array(
        'bootstrap' => realpath(__DIR__ . '/../extensions/yiistrap'), // change this if necessary
        'RestfullYii' =>realpath(__DIR__ . '/../extensions/RESTFullYii/starship/RestfullYii'),
    ),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
                'bootstrap.helpers.*',
                'bootstrap.widgets.*', // see http://www.yiiframework.com/forum/index.php/topic/52545-error-with-tbgridview-using-yiistrap/
                'bootstrap.behaviors.*',
                'application.modules.user.models.*', // for https://github.com/mishamx/yii-user
                'application.modules.user.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool

		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'rg248qy',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1','172.27.14.150'),
                    'generatorPaths' => array('bootstrap.gii'),
		),


            'user'=>array(
                'tableUsers' => 'users',
                'tableProfiles' => 'profiles',
                'tableProfileFields' => 'profiles_fields',


                 //'class' => 'auth.components.AuthWebUser',
                 //'class'=>'WebUser',
                 //'allowAutoLogin'=>true,

                    # encrypting method (php hash function)
                    'hash' => 'md5',

                    # send activation email
                    'sendActivationMail' => true,

                    # allow access for non-activated users
                    'loginNotActiv' => false,

                    # activate user on registration (only sendActivationMail = false)
                    'activeAfterRegister' => false,

                    # automatically login from registration
                    'autoLogin' => true,

                    # registration path
                    'registrationUrl' => array('/user/registration'),

                    # recovery password path
                    'recoveryUrl' => array('/user/recovery'),

                    # login form path
                    'loginUrl' => array('/user/login'),

                    # page after login
                    'returnUrl' => array('/user/profile'),

                    # page after logout
                    'returnLogoutUrl' => array('/user/login'),

                ),



            'auth' => array(
                //'strictMode' => true, // when enabled authorization items cannot be assigned children of the same type.
                'userClass' => 'User', // the name of the user model class.
                'userIdColumn' => 'id', // the name of the user id column.
                'userNameColumn' => 'username', // the name of the user name column.
                'defaultLayout' => 'application.views.layouts.main', // the layout used by the module.
                //'appLayout' => 'application.views.layouts.main',

                //'viewDir' => realpath(__DIR__ . '/../modules/auth/views'), // the path to view files to use with this module.
                'viewDir'=> 'application.modules.auth.views',
                'forceCopyAssets' => true,
              ),

        ), // modules

	// application components
	'components'=>array(
            /*
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
             *
             */


                'bootstrap' => array(
                    'class' => 'bootstrap.components.TbApi',
                ),

		'input'=>array(
				'class'         => 'CmsInput',
				'cleanPost'     => true,
				'cleanGet'      => true,
		),

		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(

			'urlFormat'=>'path',
			'rules'=>/*array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),*/
                    require(
                dirname(__FILE__).'/../extensions/RestfullYii/starship/RestfullYii/config/routes.php'
            ),
		),

            /*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
             *
             */
		// uncomment the following to use a MySQL database

		'db'=>array(
			'connectionString' => 'mysql:host='. DBHOST .';dbname=' . DBNAME,
			'emulatePrepare' => true,
                        'username' => DBUSER,
                        'password' => DBPASS,
			'charset' => 'utf8',
			'enableParamLogging'=>!IS_PUBLIC_FACING, // Turned off RCH  20121018
			'schemaCachingDuration' => 300,  // Performance enhancement? as per http://www.yiiframework.com/forum/index.php?/topic/14974-schemacachingduration/
			'enableProfiling'=> !IS_PUBLIC_FACING, // Turned off RCH  20121018
		),

			'session' => array (
					'sessionName' => 'mdfsaccess',
					//'cookieMode' => 'none',
					'class' => 'CDbHttpSession',
					'timeout'=>43200,  // 12 hours
					'cookieParams' => array(
							//'secure' => true,
							'httponly'=>true,
					),
			),
		'cache'=>array(
			   'class'=>'system.caching.CFileCache', // Performance enhancement? as per http://www.yiiframework.com/forum/index.php?/topic/14974-schemacachingduration/
			),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
			array(
					'class'=>'MPFileLogRoute', // to prevent application.log permission issues when running console (as user) & app (as web)
					'levels'=>'info, error, warning',
						'maxFileSize'=>10240,
						'maxLogFiles'=>30
				),
array(
                                        'class'=>'CDbLogRoute',
                                        'levels'=>'error, warning',
				 	'connectionID' => 'db',
					'filter'=>'CLogFilter',
                                ),
								
				array(
                    'class'=>'CEmailLogRoute',
                    'levels'=>'error',
                    'emails'=>array('russell.hutson@exertismicro-p.co.uk'),
                                        'sentFrom'=>'prizemanager@exertismicro-p.co.uk'
                ),

				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),

            'authManager' => array(
                //'class' => 'auth.components.CachedDbAuthManager',
                //'cachingDuration' => 3600,

                'behaviors' => array(
                  'auth' => array(
                    'class' => 'auth.components.AuthBehavior',

                  ),
                ),
              ),





             'user'=>array(
                 'class' => 'auth.components.AuthWebUser',
                 //'class'=>'WebUser',
                 'admins' => array('admin'), // users with full access

                ),
	), // components

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);
return $config_array;
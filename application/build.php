<?php
return [
	//生成应用公共文件
	// '__file__' => ['common.php', 'config.php', 'database.php'],
	// 'common' => [
	// 	'__dir__' => ['model'],
	// 	'model' => ['Category', 'Admin'],
	// ],

	// 'admin' => [
	// 	'__dir__' => ['controller', 'view'],
	// 	'controller' => ['Index'],
	// 	'view' => ['Index/Index'],
	// ],

	'api' => [
		'__dir__' => ['controller', 'view'],
		'controller' => ['Index', 'Image'],
	],

	'bis' => [
		'__dir__' => ['controller', 'view'],
		'controller' => ['Register', 'Login'],
	],
];
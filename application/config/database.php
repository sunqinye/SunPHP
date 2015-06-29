<?php

$config['database']['default'] = array(
	'driver'	=>	'mysql',
	'host'		=>	'localhost',
	'port'		=>	'3306',
	'user'		=>	'root',
	'password'	=>	'',
	'name'		=>	'sunphp',
	'prefix'	=>	'',
	'charset'	=>	'utf8'  //utf8/gbk
);

$config['database']['otherdb1'] = array(
    'driver'	=>	'mysql',
    'host'		=>	'192.168.1.1',
    'port'		=>	'3306',
    'user'		=>	'test',
    'password'	=>	'123456',
    'name'		=>	'test',
    'prefix'	=>	'',
    'charset'	=>	'utf8'
);
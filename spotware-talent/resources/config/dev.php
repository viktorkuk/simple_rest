<?php
require __DIR__ . '/prod.php';
$app['debug'] = true;
$app['log.level'] = Monolog\Logger::DEBUG;
$app['db.options'] = array(
  'driver' => 'pdo_mysql',
  'dbname' => 'spotware_talent', 
  'user' => 'root',
);

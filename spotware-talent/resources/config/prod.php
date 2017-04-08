<?php
$app['log.level'] = Monolog\Logger::ERROR;
$app['api.version'] = "v1";
$app['api.endpoint'] = "/api";

/**
 * SQL database file
 */
$app['db.options'] = array(
    'driver' => 'pdo_mysql',
    'dbname' => 'spotware_talent', 
    'user' => 'root',
);

//root & pass
$app['auth.token'] = '9567893500c6aeaf4eade37489246785961d7ac9351cbf59322e201f21feef53f159a53d4917c27aa7c89442050b27f8928030bf970fd898449a0b396910ba75';

//fix OPTIONS request
$app->match("{url}", function($url) use ($app) { return "OK"; })->assert('url', '.*')->method("OPTIONS");



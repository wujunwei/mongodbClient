<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 2018/2/5
 * Time: 下午9:37
 */


require "./vendor/autoload.php";

$config = [
    'connect_str' => 'mongodb://127.0.0.1:27017/test',
    'dbname'=>'test',
    'option' => [
//        'username'=>'configadmin',
//        'password'=>'uU3yTmb2c',
//        'heartbeatFrequencyMS'=>2000,
//        'replicaSet'=>'shein',
//        'readPreference'=>'primaryPreferred',
    ],
];


$test = new \Test\TestCase();
$test->bsonUnserialize(['rew' => 3212]);
$client = new \Shein\MongoDBClient('mongodb://127.0.0.1:27017/', 'test', ['heartbeatFrequencyMS'=>2000, 'readPreference'=>'primaryPreferred']);
$em = new \Shein\EntityManage($config);
var_dump($em->merge($test));
$client->getCollection('test')->insertOne($test);
print_r($client->getCollection('test')->setTypeMap(['root' => \Test\TestCase::class])->findAll());

//foreach ($client->listCollection() as $collection){
//    var_dump($collection);
//}
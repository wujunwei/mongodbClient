<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 2018/2/5
 * Time: 下午9:37
 */


require "./vendor/autoload.php";

$test = new \Test\TestCase("5a7a980dbf2afb1e7c003aa1");
$test->bsonUnserialize(['rew' => 3212]);
$client = new \Shein\MongoDBClient('mongodb://127.0.0.1:27017/', 'test', ['heartbeatFrequencyMS'=>2000, 'readPreference'=>'primaryPreferred',], ['typeMap' => ['root' => \Shein\Entity::class]]);
$em = new \Shein\EntityManage($client);
var_dump($em->merge($test));
//$client->getCollection('test')->insertOne($test);
//print_r($client->getCollection('test')->findAll());

//foreach ($client->listCollection() as $collection){
//    var_dump($collection);
//}
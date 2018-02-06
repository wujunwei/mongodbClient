<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 2018/2/5
 * Time: 下午9:37
 */


require "./vendor/autoload.php";

$test = new \Test\TestCase();

$test->bsonUnserialize(['rew' => 321, 'fda' => ['hello' => 'world']]);
$client = new \Shein\MongoDBClient('mongodb://127.0.0.1:27017/', 'test', ['heartbeatFrequencyMS'=>2000, 'readPreference'=>'primaryPreferred',], ['typeMap' => ['document' =>'array']]);
//$client->getCollection('test')->insertOne($test);
var_dump($client->getCollection('test')->findAll());

//foreach ($client->listCollection() as $collection){
//    var_dump($collection);
//}
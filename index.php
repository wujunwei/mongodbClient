<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 2018/2/5
 * Time: 下午9:37
 */

//require "./vendor/auto";
include "./src/Entity.php";
$test = new \Shein\Entity();

$test->bsonUnserialize(['rew' => 321, 'fda' => ['hello' => 'world']]);
var_dump($test->bsonSerialize());
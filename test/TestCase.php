<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-02-06
 * Time: 下午 3:20
 */

namespace Test;


use Shein\Entity;

/**
 * Class TestCase
 * @table test
 * @table test
 * @package Test
 */
class TestCase extends Entity
{

    /**
     *
     * @return array
     */
    public function convert()
    {
        return get_object_vars($this);
    }

    /**
     * Constructs the object from a BSON array or document
     * Called during unserialization of the object from BSON.
     * The properties of the BSON array or document will be passed to the method as an array.
     * @link https://php.net/manual/en/mongodb-bson-unserializable.bsonunserialize.php
     * @param array $data Properties within the BSON array or document.
     */
    public function bsonUnserialize(array $data)
    {
        foreach ($data as $key => $datum){
            $this->{$key} = $datum;
        }
    }
}
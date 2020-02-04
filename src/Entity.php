<?php

namespace FirstW;


use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Persistable;

/**
 * @property ObjectId _id
 */
class Entity implements Persistable
{

    public function __construct($_id = null)
    {
        $this->_id = new ObjectId($_id);
    }

    /**
     * Provides an array or document to serialize as BSON
     * Called during serialization of the object to BSON. The method must return an array or stdClass.
     * Root documents (e.g. a MongoDB\BSON\Serializable passed to MongoDB\BSON\fromPHP()) will always be serialized as a BSON document.
     * For field values, associative arrays and stdClass instances will be serialized as a BSON document and sequential arrays (i.e. sequential, numeric indexes starting at 0) will be serialized as a BSON array.
     * @link http://php.net/manual/en/mongodb-bson-serializable.bsonserialize.php
     * @return array|object An array or stdClass to be serialized as a BSON array or document.
     */
    public function bsonSerialize()
    {
        return get_object_vars($this);
    }


    /**
     * Constructs the object from a BSON array or document
     * Called during unserialization of the object from BSON.
     * The properties of the BSON array or document will be passed to the method as an array.
     * @link http://php.net/manual/en/mongodb-bson-unserializable.bsonunserialize.php
     * @param array $data Properties within the BSON array or document.
     */
    public function bsonUnserialize(array $data)
    {
        foreach($data as $key => $value){
            $this->$key = $value;
        }
    }

    /**
     * @return array
     */
    public function convert()
    {
        $vars = $this->bsonSerialize();
        if (isset($vars['__pclass'])){
            unset($vars['__pclass']);
        }

        if (isset($vars['_id'])){
            unset($vars['_id']);
        }
        return $vars;
    }
}
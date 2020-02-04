<?php

namespace FirstW;


use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Persistable;

/**
 * @property ObjectId _id
 */
abstract class Entity  implements Persistable
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
        $arr = $this->convert();
        if (!isset($arr['_id'])){
            $arr['_id'] = $this->_id;
        }
        return $arr;
    }


    /**
     *
     * @return array
     */
    abstract public function convert();

}
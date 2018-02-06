<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 2018/2/5
 * Time: 下午10:12
 */

namespace Shein;


use MongoDB\DeleteResult;
use MongoDB\InsertOneResult;
use MongoDB\UpdateResult;

class EntityManage implements EntityHandle
{
    /**
     * @var Entity
     */
    private $unit = null;
    /**
     * @var MongoDBClient
     */
    private $client = null;
    public function __construct(MongoDBClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param Entity $entity
     * @return Entity[]
     */
    public function find(Entity $entity)
    {

    }

    /**
     * @param Entity $entity
     * @return bool
     */
    public function remove(Entity $entity)
    {
        if (!is_null($this->unit)){
            return false;
        }
        $this->unit = $entity;
        return true;
    }

    /**
     * @param Entity $entity
     * @return bool
     */
    public function merge(Entity $entity)
    {
        // TODO: Implement merge() method.
    }

    /**
     * @param Entity $entity
     * @return bool
     */
    public function persist(Entity $entity)
    {
        // TODO: Implement persist() method.
    }

    /**
     * @return DeleteResult|InsertOneResult|UpdateResult
     */
    public function flush()
    {
        // TODO: Implement flush() method.
    }

    private function checkID()
    {

    }
}
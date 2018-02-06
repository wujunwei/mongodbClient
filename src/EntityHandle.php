<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-02-06
 * Time: 下午 5:49
 */

namespace Shein;


use MongoDB\DeleteResult;
use MongoDB\InsertOneResult;
use MongoDB\UpdateResult;

interface EntityHandle
{
    /**
     * @param Entity $entity
     * @return Entity[]|Entity
     */
    public function find(Entity $entity);

    /**
     * @param Entity $entity
     * @return bool
     */
    public function remove(Entity $entity);

    /**
     * @param Entity $entity
     * @return bool
     */
    public function merge(Entity $entity);

    /**
     * @param Entity $entity
     * @return bool
     */
    public function persist(Entity $entity);

    /**
     * @return DeleteResult|InsertOneResult|UpdateResult
     */
    public function flush();
}
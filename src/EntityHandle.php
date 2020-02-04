<?php

namespace FirstW;


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
     * @return DeleteResult
     */
    public function remove(Entity $entity);

    /**
     * @param Entity $entity
     * @return UpdateResult
     */
    public function merge(Entity $entity);

    /**
     * @param Entity $entity
     * @return InsertOneResult
     */
    public function persist(Entity $entity);
}
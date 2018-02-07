<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 2018/2/5
 * Time: 下午10:12
 */

namespace Shein;


use MongoDB\DeleteResult;
use MongoDB\UpdateResult;
use phpDocumentor\Reflection\DocBlockFactory;

class EntityManage implements EntityHandle
{
    const TABLE_TAG = 'table';
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
        $collection = $this->loadCollection($entity);
        return $collection->setCondition($entity->convert())->findAll();
    }

    /**
     * @param Entity $entity
     * @return DeleteResult
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function remove(Entity $entity)
    {
        $collection = $this->loadCollection($entity);
        return $collection->where('_id', $entity->_id)->drop();
    }

    /**
     * @param Entity $entity
     * @return UpdateResult
     */
    public function merge(Entity $entity)
    {
        $collection = $this->loadCollection($entity);
        return $collection->where('_id', $entity->_id)->updateOne(['$set' => $entity]);
    }

    /**
     * @param Entity $entity
     * @return int
     */
    public function persist(Entity $entity)
    {
        return $this->loadCollection($entity)->insertOne($entity);
    }

    /**
     * @param $class
     * @return Connection
     * @throws \Exception
     * @throws \ReflectionException
     */
    private function loadCollection($class)
    {
        $reflect = new \ReflectionClass($class);
        $document = $reflect->getDocComment();
        $doc = DocBlockFactory::createInstance()->create($document);
        if (!$doc->hasTag(self::TABLE_TAG)){
            throw new \Exception();//todo
        }
        $tags = $doc->getTagsByName(self::TABLE_TAG);
        $collect = (string)array_shift($tags);
        return $this->client->getCollection($collect);
    }

    public function getClient()
    {
        return $this->client;
    }
}
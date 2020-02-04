<?php

namespace FirstW;


use Exception;
use MongoDB\DeleteResult;
use MongoDB\UpdateResult;
use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionClass;
use ReflectionException;

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
     * @throws ReflectionException
     */
    public function find(Entity $entity)
    {
        $collection = $this->loadCollection($entity);
        return $collection->setCondition($entity->convert())->findAll();
    }

    /**
     * @param Entity $entity
     * @return DeleteResult
     * @throws Exception
     * @throws ReflectionException
     */
    public function remove(Entity $entity)
    {
        $collection = $this->loadCollection($entity);
        return $collection->where('_id', $entity->_id)->dropOne();
    }

    /**
     * @param Entity $entity
     * @return UpdateResult
     * @throws ReflectionException
     */
    public function merge(Entity $entity)
    {
        $collection = $this->loadCollection($entity);
        return $collection->where('_id', $entity->_id)->updateOne(['$set' => $entity]);
    }

    /**
     * @param Entity $entity
     * @return int
     * @throws ReflectionException
     */
    public function persist(Entity $entity)
    {
        return $this->loadCollection($entity)->insertOne($entity);
    }

    /**
     * @param $class
     * @return Connection
     * @throws Exception
     * @throws ReflectionException
     */
    private function loadCollection($class)
    {
        $reflect = new ReflectionClass($class);
        $document = $reflect->getDocComment();
        $doc = DocBlockFactory::createInstance()->create($document);
        if (!$document){
            throw new Exception('Table tag is not exist !');
        }
        if (!$doc->hasTag(self::TABLE_TAG)) {
            throw new Exception("Can't find the table tag");
        }
        $tags = $doc->getTagsByName(self::TABLE_TAG);
        $collect = (string)array_shift($tags);
        return $this->client->getCollection($collect)->setTypeMap(['document' => get_class($class)]);

    }

    public function getClient()
    {
        return $this->client;
    }
}
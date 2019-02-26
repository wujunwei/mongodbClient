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

    private $config = null;

    /**
     * EntityManage constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->client = new MongoDBClient($config['connect_str'], $config['dbname'], $config['option']);
    }

    /**
     * @param Entity $entity
     * @return Entity[]
     * @throws \ReflectionException
     */
    public function find(Entity $entity)
    {
        $collection = $this->loadCollection($entity);
        $condition = $entity->bsonSerialize();
        return $collection->setCondition($condition)->findAll();
    }

    /**
     * @param Entity $entity
     * @return Entity
     * @throws \ReflectionException
     */
    public function findOne(Entity $entity)
    {
        $collection = $this->loadCollection($entity);
        $condition = $entity->bsonSerialize();
        return $collection->setCondition($condition)->findOne();
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
        return $collection->where('_id', $entity->_id)->dropOne();
    }

    /**
     * @param Entity $entity
     * @return UpdateResult
     * @throws \ReflectionException
     */
    public function merge(Entity $entity)
    {
        $collection = $this->loadCollection($entity);
        return $collection->where('_id', $entity->_id)->updateOne(['$set' => $entity]);
    }

    /**
     *  the count of insert successfully
     * @param Entity $entity
     * @return int
     * @throws \ReflectionException
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
        if (!$document){
            throw new \Exception('Table tag is not exist !');
        }
        $doc = DocBlockFactory::createInstance()->create($document);
        if (!$doc->hasTag(self::TABLE_TAG)){
            throw new \Exception('Table tag is not exist !');
        }
        $tags = $doc->getTagsByName(self::TABLE_TAG);
        $collect = (string)array_shift($tags);
        $collection =  $this->client->getCollection($collect);
        $collection->setTypeMap(['document' => get_class($class)]);
        return $collection;
    }

    public function getClient()
    {
        return $this->client;
    }
}
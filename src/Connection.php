<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-02-06
 * Time: 下午 12:33
 */

namespace Shein;
use MongoDB\BSON\ObjectID;
use MongoDB\InsertManyResult;
use MongoDB\InsertOneResult;
use MongoDB\Collection;

class Connection
{
    const DESC = -1;
    const ASC = 1;

    /**
     * @var Collection
     */
    private $collection;

    private $condition = [];
    private $queryOption = [];
    /**
     * @var InsertOneResult|InsertManyResult
     */
    private $lastInsertResult = null;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param string $field
     * @param int $order
     * @return $this
     */
    public function orderBy($field = '', $order = self::DESC){
        $this->queryOption['sort'] = [$field => $order];
        return $this;
    }

    /**
     * @param $start
     * @param $offset
     * @return $this
     */
    public function limit($start = 0, $offset = 1)
    {
        $this->queryOption['limit'] = intval($offset);
        $this->queryOption['skip'] = intval($start);
        return $this;
    }

    /**
     * @param $field
     * @param $condition
     * @return $this
     */
    public function where($field, $condition)
    {
        if ($field === '_id' && !is_array($condition)){
            $condition = new ObjectID(strval($condition));
        }
        $this->condition[$field] = $condition;
        return $this;
    }

    private function initQueryCondition()
    {
        $this->condition = [];
        $this->queryOption = [];
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $result = $this->collection->find($this->condition, $this->queryOption);
        $this->initQueryCondition();
        return $result->toArray();
    }

    /**
     * @return array|Entity
     */
    public function findOne()
    {
        $result = $this->collection->findOne($this->condition, $this->queryOption);
        $this->initQueryCondition();
        return $result;
    }
//todo 将所有操作opt数组做成配置
    public function insertOne($document, $opt = [])
    {
        $this->lastInsertResult = $this->collection->insertOne($document, $opt);
        return $this->lastInsertResult->getInsertedCount();
    }

    public function insertMany($document, $opt = [])
    {
        $this->lastInsertResult = $this->collection->insertMany($document, $opt);
        return $this->lastInsertResult->getInsertedCount();
    }

    public function getLastInsertId()
    {
        if ($this->lastInsertResult instanceof InsertManyResult){
            return $this->lastInsertResult->getInsertedIds();
        }elseif($this->lastInsertResult instanceof InsertOneResult){
            return $this->lastInsertResult->getInsertedId();
        }
        return null;
    }

    /**
     * @param $update
     * @param array $option
     * @return \MongoDB\UpdateResult
     */
    public function updateOne($update, $option = [])
    {
        $result = $this->collection->updateOne($this->condition, $update, $option);
        $this->initQueryCondition();
        return $result;
    }

    /**
     * @param $updates
     * @param array $option
     * @return \MongoDB\UpdateResult
     * @throws \Exception
     */
    public function updateMany($updates, $option = [])
    {
        if (empty($this->condition)){
            throw new \Exception();//todo
        }
        $result = $this->collection->updateMany($this->condition, $updates, $option);
        $this->initQueryCondition();
        return $result;
    }
}
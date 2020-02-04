<?php

namespace FirstW;

use Exception;
use MongoDB\BSON\ObjectID;
use MongoDB\BulkWriteResult;
use MongoDB\DeleteResult;
use MongoDB\Driver\Exception\RuntimeException as DriverRuntimeException;
use MongoDB\Exception\InvalidArgumentException;
use MongoDB\Exception\UnsupportedException;
use MongoDB\InsertManyResult;
use MongoDB\InsertOneResult;
use MongoDB\Collection;
use MongoDB\UpdateResult;
use Traversable;

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
     * @param string|array $field
     * @param int $order
     * @return $this
     */
    public function orderBy($field = '', $order = self::DESC)
    {
        if (is_array($field)) {
            $this->queryOption['sort'] = $field;
        } else {
            $this->queryOption['sort'] = [$field => $order];
        }
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
        if ($field === '_id' && !is_array($condition)) {
            $condition = new ObjectID(strval($condition));
        }
        $this->condition[$field] = $condition;
        return $this;
    }

    /**
     * @param array $condition
     * @return Connection
     */
    public function setCondition($condition = [])
    {
        $this->condition = $condition;
        return $this;
    }

    private function initQueryCondition()
    {
        $this->condition = [];
        $this->queryOption = [];
    }

    /**
     * @param array $field
     * @return array
     */
    public function findAll($field = [])
    {
        if (!empty($field)) {
            $this->queryOption += ['projection' => $field];
        }
        $result = $this->collection->find($this->condition, $this->queryOption);
        $this->initQueryCondition();
        return $result->toArray();
    }

    /**
     * @param array $field
     * @return array|Entity
     */
    public function findOne($field = [])
    {
        if (!empty($field)) {
            $this->queryOption += ['projection' => $field];
        }
        $result = $this->collection->findOne($this->condition, $this->queryOption);
        $this->initQueryCondition();
        return $result;
    }

    /**
     * @param $document
     * @param array $opt
     * @return int
     */
    public function insertOne($document, $opt = [])
    {
        $this->lastInsertResult = $this->collection->insertOne($document, $opt);
        return $this->lastInsertResult->getInsertedCount();
    }

    /**
     * @param $documents
     * @param array $opt
     * @return int
     */
    public function insertMany($documents, $opt = [])
    {
        $this->lastInsertResult = $this->collection->insertMany($documents, $opt);
        return $this->lastInsertResult->getInsertedCount();
    }

    public function getLastInsertId()
    {
        if ($this->lastInsertResult instanceof InsertManyResult) {
            return $this->lastInsertResult->getInsertedIds();
        } elseif ($this->lastInsertResult instanceof InsertOneResult) {
            return $this->lastInsertResult->getInsertedId();
        }
        return null;
    }

    /**
     * @param $update
     * @param array $option
     * @return UpdateResult
     */
    public function updateOne($update, $option = [])
    {
        $result = $this->collection->updateOne($this->condition, $update, $option);
        $this->initQueryCondition();
        return $result;
    }

    /**
     * @return DeleteResult
     * @throws Exception
     */
    public function dropOne()
    {
        if (!isset($this->condition['_id'])) {
            throw new Exception("It 's not safe droping document without a object_id");
        }
        $result = $this->collection->deleteOne($this->condition);
        $this->initQueryCondition();
        return $result;
    }

    public function dropMany()
    {
        $result = $this->collection->deleteMany($this->condition);
        $this->initQueryCondition();
        return $result;
    }

    /**
     * @param $updates
     * @param array $option
     * @return UpdateResult
     * @throws Exception
     */
    public function updateMany($updates, $option = [])
    {
        if (empty($this->condition)) {
            throw new Exception("It 's not safe updating document without conditions");
        }
        $result = $this->collection->updateMany($this->condition, $updates, $option);
        $this->initQueryCondition();
        return $result;
    }

    /**
     * @return int
     */
    public function count()
    {
        $result = $this->collection->count($this->condition, $this->queryOption);
        $this->initQueryCondition();
        return $result;
    }

    /**
     * @param array $pipeline
     * @param array $options
     * @return Traversable
     */
    public function aggregate(array $pipeline, array $options = [])
    {
        return $this->collection->aggregate($pipeline, $options);
    }

    /**
     * Executes multiple write operations.
     *
     * @param array[] $operations List of write operations
     * @param array $options Command options
     * @return BulkWriteResult
     *
     * @throws UnsupportedException if options are not supported by the selected server
     * @throws InvalidArgumentException for parameter/option parsing errors
     * @throws DriverRuntimeException for other driver errors (e.g. connection errors)
     * @see BulkWrite::__construct() for supported options
     */
    public function bulkWrite(array $operations, array $options = [])
    {
        return $this->collection->bulkWrite($operations, $options);
    }

    public function setTypeMap($typeMap)
    {
        $this->queryOption['typeMap'] = $typeMap;
        return $this;
    }
}
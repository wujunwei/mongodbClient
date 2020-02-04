<?php

namespace FirstW;


use MongoDB\Client;
use MongoDB\Database;
use MongoDB\GridFS\Bucket;
use MongoDB\Model\CollectionInfoIterator;


class MongoDBClient
{

    /**
     * @var Database
     */
    private $database = null;
    /**
     * @var
     */
    private $connection = null;


    /**
     * MongoDBClient constructor.
     * @param $dsn
     * @param $database
     * @param array $uriOptions
     * @param array $driverOptions
     */
    public function __construct($dsn, $database, $uriOptions = [], $driverOptions = [])
    {
        if (!isset($driverOptions['typeMap'])) {
            $driverOptions = ['typeMap' => ['root' => 'array']];
        }
        $client = new Client($dsn, $uriOptions, $driverOptions);
        $this->database = $client->selectDatabase($database);
    }

    /**
     * @param string $collection
     * @return Connection
     */
    public function getCollection($collection = null)
    {
        if (!is_null($collection)) {
            $this->connection = new Connection($this->database->selectCollection($collection));
        }
        return $this->connection;
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->database->getDatabaseName();
    }

    /**
     * @param array $options
     * @return Bucket
     */
    public function getGridFSBucket(array $options = [])
    {
        return $this->database->selectGridFSBucket($options);
    }

    /**
     * @param array $option
     */
    public function setOpt($option = [])
    {
        $this->database = $this->database->withOptions($option);
    }

    /**
     * @return CollectionInfoIterator
     */
    public function listCollection()
    {
        return $this->database->listCollections();
    }

}
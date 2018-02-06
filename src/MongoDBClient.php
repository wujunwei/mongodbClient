<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-10-18
 * Time: 下午 4:12
 */

namespace Shein;



use MongoDB\Client;
use MongoDB\Database;


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
        if (!isset($driverOptions['typeMap'])){
            $driverOptions = ['typeMap' => ['root' => 'array']];
        }
        $client = new Client($dsn, $uriOptions, $driverOptions);
        $this->database = $client->selectDatabase($database);
    }

    /**
     * @param string $collection
     * @return null|Connection
     */
    public function getCollection($collection = '')
    {
        $this->connection = new Connection($this->database->selectCollection($collection));
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
     * @param array $option
     */
    public function setOpt($option = [])
    {
        $this->database = $this->database->withOptions($option);
    }

    public function listCollection()
    {
        return $this->database->listCollections();
    }

}
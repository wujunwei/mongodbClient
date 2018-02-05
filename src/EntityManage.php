<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 2018/2/5
 * Time: 下午10:12
 */

namespace Shein;


class EntityManage
{
    /**
     * @var MongoDBClient
     */
    private $client = null;
    public function __construct(MongoDBClient $client)
    {
        $this->client = $client;

    }

}
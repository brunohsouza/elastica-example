<?php
/**
 * Created by PhpStorm.
 * User: brunohsouza
 * Date: 19/12/18
 * Time: 20:26
 */

namespace App\Service;


use Elastica\Client;
use Elastica\Index;
use Elastica\Type;

class IndexService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Index
     */
    private $index;

    /**
     * @var Type
     */
    private $type;

    /**
     * IndexService constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Creates an index
     * @param string $indexName
     * @return Index
     */
    public function createIndex(string $indexName) :Index
    {
        $this->index = $this->client->getIndex($indexName);
        if (!$this->index->exists()) {
            $this->index->create();
        }
        return $this->index;
    }

    /**
     * Creates a type
     * @param string $indexName
     * @param string $typeName
     * @return Type
     */
    public function createType(string $indexName, string $typeName) :Type
    {
        $this->index = $this->client->getIndex($indexName);
        $this->type = $this->index->getType($typeName);
        if (!$this->type->exists()) {
            $this->type = new Type($this->index, $typeName);
        }
        return $this->type;
    }

}
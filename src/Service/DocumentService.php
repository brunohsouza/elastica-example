<?php

namespace App\Service;

use Elastica\Bulk;
use Elastica\Document;
use Elastica\Index;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Response;
use Elastica\Script\Script;
use Elastica\Type;

class DocumentService
{
    /**
     * Container service object contains the Index  or Type object
     * @var Index | Type
     */
    private $container;

    /**
     * DocumentService constructor. Receives and add value to the container service
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Create a document based on the parameters given. One document / time
     * @param $params
     * @return array
     */
    public function createDocument($params)
    {
        $params = json_decode($params, true);
        $document = new Document('', $params);
        $doc = $this->container->addDocument($document);
        return $doc->getData();
    }

    /**
     * Search for parameters given exactly as received
     * @param null $params
     * @return mixed
     */
    public function search($params = null)
    {
        $options = $this->getOptions($params);
        $query = new Query();
        $query->setParams($options);
        $docs = $this->container->search($options);

        return $docs->getResponse()->getData()['hits']['hits'];
    }

    /**
     * Search for a value indexed that matches the parameter given
     * @param null $params
     * @return Response | array
     */
    public function match($params = null)
    {
        $match = new Match();
        $match->setFieldQuery($params['dsTerm'], $params['valTerm']);
        $boolQuery = new BoolQuery();
        $boolQuery->addMust($match);
        $query = new Query();
        $query->setQuery($boolQuery);
        $options = $this->getOptions($params);
        $docs = $this->container->search($query, $options);

        return $docs->getResponse()->getData()['hits']['hits'];
    }

    /**
     * Return all data that is present on the index and type given
     * @return array
     */
    public function matchAll() :array
    {
        $query = new Query();
        $docs =  $this->container->search($query);
        $resultSet = $docs->getResults();

        $data = [];
        foreach ($resultSet as $key => $result) {
            $data[$key] = $result->getData();
        }
        return $data;
    }

    /**
     * Return the map of the index passing on the constructor of the class
     * @return mixed
     */
    public function getMap()
    {
        return $this->container->getMapping();
    }

    /**
     * Build an array of options to be used on the query
     * @param $params
     * @return array
     */
    public function getOptions($params) :array
    {
        $options = [];
        if (isset($params['size'])) {
            $options['size'] = $params['size'];
        }

        if (isset($params['from'])) {
            $options['from'] = $params['from'];
        }

        if (isset($params['score'])) {
            $options['_score'] = $params['score'];
        }

        return $options;
    }

    /**
     * Build a query string within the terms to search
     * @param $params
     * @return array
     */
    public function getQueryTerm($params)
    {
        $queryTerm = [];
        if (isset($params['dsTerm'])) {
            $queryTerm['query']['term'] = [$params['dsTerm'] => $params['valTerm']];
        }

        return $queryTerm;
    }

    /**
     * Update a document with parameters given and if there is some data different
     * @param $id
     * @param $params
     * @return array
     */
    public function updateDocument($id, $params)
    {
        $params = json_decode($params, true);
        $document = new Document();
        $document->setData($params);
        $document->setId($id);
        $document->setIndex($this->container->getIndex()->getName());
        $document->setType($this->container->getName());
        // update the document if exists. Else insert a new document
        $script = new Script('ctx._source.field2 += count; ctx._source.remove("field3")');
        $script->setUpsert($document);
        $script->setId($id);
        $doc = $this->container->updateDocument($document);

        return $doc->getData($doc);
    }

    /**
     * Delete a document with id given
     * @param $id
     * @return array
     */
    public function deleteDocumentById($id)
    {
        $document = new Document();
        $document->setId($id);
        $document->setIndex($this->container->getIndex()->getName());
        $document->setType($this->container->getName());

        return $this->container->deleteById($id)->getData();
    }

    /**
     * Populates the index with the parameters given as array
     * @param array $params
     * @return Bulk\Response[]
     * @throws \Exception
     */
    public function populate($params)
    {
        $arrDocuments = [];
        foreach ($params as $data) {
            $arrDocuments[] = $this->container->createDocument('', $data);
        }
        if (empty($arrDocuments)) {
            throw new \Exception('No data to be indexed');
        }
        $client = $this->container->getIndex()->getClient();
        $bulk = new Bulk($client);
        $bulk->setType($this->container->getName());
        $bulk->addDocuments($arrDocuments);
        return $bulk->send()->getData()['items'];
    }
}
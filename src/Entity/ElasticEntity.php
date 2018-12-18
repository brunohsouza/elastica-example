<?php
/**
 * Created by PhpStorm.
 * User: brunohsouza
 * Date: 12/12/18
 * Time: 17:30
 */

namespace App\Entity;


class ElasticEntity
{
    /**
     * @var string $index
     */
    private $index;

    /**
     * @var string $type
     */
    private $type;

    /**
     * @var string $id
     */
    private $id;

    /**
     * @var array $body
     */
    private $body;

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param string $index
     * @return ElasticEntity
     */
    public function setIndex(string $index): ElasticEntity
    {
        $this->index = $index;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return ElasticEntity
     */
    public function setType(string $type): ElasticEntity
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return ElasticEntity
     */
    public function setId(string $id): ElasticEntity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param array $body
     * @return ElasticEntity
     */
    public function setBody($body): ElasticEntity
    {
        $this->body = json_encode($body);
        return $this;
    }
}
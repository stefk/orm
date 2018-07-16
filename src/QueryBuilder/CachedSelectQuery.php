<?php

namespace Anytime\ORM\QueryBuilder;

use Anytime\ORM\EntityManager\Entity;

class CachedSelectQuery extends QueryAbstract implements SelectQueryInterface
{
    const FETCH_DATA_FORMAT_ENTITY = 'entity';
    const FETCH_DATA_FORMAT_ARRAY = 'array';

    /**
     * @var bool
     */
    private $fetchDone = true;

    /**
     * @var string
     */
    private $fetchDataFormat = self::FETCH_DATA_FORMAT_ENTITY;

    /**
     * @var int
     */
    private $currentFetchIndex = 0;

    /**
     * @var array
     */
    private $cachedData = [];

    /**
     * CachedSelectQuery constructor.
     * @param array $cachedData
     */
    public function __construct(array $cachedData)
    {
        $this->cachedData = $cachedData;
    }

    /**
     * @param string $fetchDataFormat
     * @return SelectQueryInterface
     */
    public function setFetchDataFormat(string $fetchDataFormat): SelectQueryInterface
    {
        $this->fetchDataFormat = $fetchDataFormat;
        return $this;
    }

    /**
     * @return array|Entity|null
     */
    public function fetchOne()
    {
        $this->currentFetchIndex = 0;
        $result = $this->fetch();
        $this->fetchDone = true;
        return $result;
    }

    /**
     * @return array|Entity|null
     */
    public function fetch()
    {
        if($this->fetchDone) {
            $this->currentFetchIndex = 0;
            $this->fetchDone = false;
        }

        if(array_key_exists($this->currentFetchIndex, $this->cachedData)) {
            $fetchedData = $this->cachedData[$this->currentFetchIndex];
            $this->currentFetchIndex++;
            if($this->entityClass && $this->fetchDataFormat === self::FETCH_DATA_FORMAT_ENTITY) {
                return new $this->entityClass($fetchedData);
            } else {
                return $fetchedData;
            }
        } else {
            $this->fetchDone = true;
            return null;
        }
    }

    /**
     * @return array|Entity
     */
    public function fetchAll(): array
    {
        $this->currentFetchIndex = 0;
        $results = [];
        $fetchedData = $this->cachedData;

        if($this->entityClass && $this->fetchDataFormat === self::FETCH_DATA_FORMAT_ENTITY) {
            foreach($fetchedData as $result) {
                $entity = new $this->entityClass($result);
                $results[] = $entity;
            }
            $this->fetchDone = true;
            return $results;
        } else {
            $this->fetchDone = true;
            return $fetchedData;
        }
    }

    /**
     * @return mixed
     */
    public function fetchSingleScalarResult()
    {
        if(array_key_exists(0, $this->cachedData)) {
            foreach($this->cachedData[0] as $key => $value) {
                return $value;
            }
        }
        return null;
    }
}

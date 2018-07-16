<?php

namespace Anytime\ORM\Cache;

use Anytime\ORM\EntityManager\Entity;
use Psr\SimpleCache\CacheInterface;

class CacheResults
{
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var CacheKey
     */
    private $cacheKey;

    /**
     * @var string
     */
    private $lastHashedKey;

    /**
     * CacheResults constructor.
     * @param CacheInterface $cache
     * @param CacheKey $cacheKey
     */
    public function __construct(CacheInterface $cache, CacheKey $cacheKey)
    {
        $this->cache = $cache;
        $this->cacheKey = $cacheKey;
    }

    /**
     * @param string $tableName
     * @param array $primaryKeyValues
     * @return bool
     */
    public function getEntityCachedData(string $tableName, array $primaryKeyValues): bool
    {
        $key = $this->cacheKey->getEntityCacheKey($tableName, $primaryKeyValues);
        $this->lastHashedKey = $key;
        return $this->cache->get($key);
    }

    /**
     * @param string $tableName
     * @param string $sqlQuery
     * @param array $sqlParams
     * @return mixed
     */
    public function getQueryResultCachedData(string $tableName, string $sqlQuery, array $sqlParams)
    {
        $key = $this->cacheKey->getQueryResultCacheKey($tableName, $sqlQuery, $sqlParams);
        $this->lastHashedKey = $key;
        return $this->cache->get($key);
    }

    /**
     * @param Entity $entity
     * @param array $data
     * @param bool $useLastHashedKey
     * @return bool
     */
    public function setEntityCache(Entity $entity, array $data, bool $useLastHashedKey = false): bool
    {
        if($useLastHashedKey && $this->lastHashedKey) {
            $key = $this->lastHashedKey;
        } else {
            $key = $this->cacheKey->getEntityCacheKey($entity::TABLENAME, $entity->extractPrimaryKeyValues());
        }

        return $this->cache->set($key, $data);
    }

    /**
     * @param string $tableName
     * @param string $sqlQuery
     * @param string $sqlParams
     * @param array $data
     * @param bool $useLastHashedKey
     * @return bool
     */
    public function setQueryResultsCache(string $tableName, string $sqlQuery, string $sqlParams, array $data, bool $useLastHashedKey): bool
    {
        if($useLastHashedKey && $this->lastHashedKey) {
            $key = $this->lastHashedKey;
        } else {
            $key = $this->cacheKey->getEntityCacheKey($tableName, $sqlQuery, $sqlParams);
        }

        return $this->cache->set($key, $data);
    }

    /**
     * Used to optimize performance avoiding double hash for same string (check if exists + create cache)
     * @return string|null
     */
    public function getLastHashedKey()
    {
        return $this->lastHashedKey;
    }
}
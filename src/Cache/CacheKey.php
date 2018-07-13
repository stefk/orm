<?php

namespace Anytime\ORM\Cache;

class CacheKey
{
    /**
     * @param string $tableName
     * @param string $sqlQuery
     * @param array $sqlParams
     * @return string
     */
    public function getQueryResultCacheKey(string $tableName, string $sqlQuery, array $sqlParams)
    {
        return 'ORM-SQL-RESULTS_' . $tableName . '_' . hash('fnv164', $sqlQuery) . hash('fnv164', print_r($sqlParams, true));
    }

    /**
     * @param string $tableName
     * @param array $primaryKeyValues
     * @return string
     */
    public function getEntityCacheKey(string $tableName, array $primaryKeyValues)
    {
        return 'ORM-ENTITY_' . $tableName . '_' . hash('fnv164',  print_r($primaryKeyValues, true));
    }
}
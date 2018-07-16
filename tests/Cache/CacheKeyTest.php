<?php

namespace Anytime\ORM\Tests\Cache;

use Anytime\ORM\Cache\CacheKey;
use PHPUnit\Framework\TestCase;

class CacheKeyTest extends TestCase
{
    /**
     * @group Cache
     * @group CacheKey
     * @param string $tableName
     * @param string $sqlQuery
     * @param array $sqlParams
     * @param string $expectedKey
     * @dataProvider cacheKeyResultCacheProvider
     */
    public function testGetQueryResultCacheKey(string $tableName, string $sqlQuery, array $sqlParams, string $expectedKey)
    {
        $cacheKey = new CacheKey();
        $this->assertSame($expectedKey, $cacheKey->getQueryResultCacheKey($tableName, $sqlQuery, $sqlParams));
    }

    /**
     * @group Cache
     * @group CacheKey
     * @dataProvider cacheKeyEntityProvider
     * @param string $tableName
     * @param array $pkeys
     * @param string $expectedKey
     */
    public function testGetEntityCacheKey(string $tableName, array $pkeys, string $expectedKey)
    {
        $cacheKey = new CacheKey();
        $this->assertSame($expectedKey, $cacheKey->getEntityCacheKey($tableName, $pkeys));
    }

    /**
     * @return array
     */
    public function cacheKeyResultCacheProvider()
    {
        return [
            ['users', 'SELECT * FROM users WHERE id = :id', ['id' => 1], 'ORM-SQL-RESULTS_users_2f60ff83138d076fa4c764911b74bd06'],
            ['users', 'SELECT * FROM users', [], 'ORM-SQL-RESULTS_users_f394a834e47b5d552c94a0442da4b385']
        ];
    }

    /**
     * @return array
     */
    public function cacheKeyEntityProvider()
    {
        return [
            ['users', [1], 'ORM-ENTITY_users_7b5de198fe1115b5'],
            ['users', [1, 1], 'ORM-ENTITY_users_b1c37c9333541e7a']
        ];
    }
}
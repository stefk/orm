<?php

namespace Anytime\ORM\QueryBuilder;

interface SelectQueryInterface
{
    /**
     * @param string $fetchDataFormat
     * @return SelectQueryInterface
     */
    public function setFetchDataFormat(string $fetchDataFormat): SelectQueryInterface;

    /**
     * @return mixed
     */
    public function fetchOne();

    /**
     * @return mixed
     */
    public function fetch();

    /**
     * @return array
     */
    public function fetchAll(): array;

    /**
     * @return mixed
     */
    public function fetchSingleScalarResult();
}

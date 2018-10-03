<?php

namespace Anytime\ORM\QueryBuilder;

use Anytime\ORM\EntityManager\DBConnection;

class QueryAbstract
{
    /**
     * @var DBConnection
     */
    protected $DBConnection;

    /**
     * @var \PDOStatement
     */
    protected $PDOStatement;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var string
     */
    protected $entityClass;


    /**
     * Query constructor.
     * @param DBConnection $DBConnection
     * @param \PDOStatement $PDOStatement
     * @param $parameters
     */
    public function __construct(DBConnection $DBConnection, \PDOStatement $PDOStatement, $parameters)
    {
        $this->DBConnection = $DBConnection;
        $this->PDOStatement = $PDOStatement;
        $this->parameters = $parameters;
    }

    /**
     * @param string $entityClass
     * @return QueryAbstract
     */
    public function setEntityClass(string $entityClass): QueryAbstract
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    /**
     * @param \PDOStatement $PDOStatement
     */
    protected function throwPdoError(\PDOStatement $PDOStatement)
    {
        $errInfo = $PDOStatement->errorInfo();
        if(array_key_exists(1, $errInfo) && $errInfo[1]) {
            $msg = array_key_exists(2, $errInfo) ? $errInfo[2] : 'Unknown error';
            throw new \RuntimeException($msg, (int)$errInfo[1]);
        }
    }
}

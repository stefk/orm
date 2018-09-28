<?php

namespace Anytime\ORM\QueryBuilder;

class QueryAbstract
{
    /**
     * @var \PDO
     */
    protected $pdo;

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
     * @var callable
     */
    protected $fnDatabaseSwitcher;

    /**
     * Query constructor.
     * @param \PDO $pdo
     * @param \PDOStatement $PDOStatement
     * @param $parameters
     * @param callable $fnDatabaseSwitcher
     */
    public function __construct(\PDO $pdo, \PDOStatement $PDOStatement, $parameters, callable $fnDatabaseSwitcher)
    {
        $this->pdo = $pdo;
        $this->PDOStatement = $PDOStatement;
        $this->parameters = $parameters;
        $this->fnDatabaseSwitcher = $fnDatabaseSwitcher;
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

    /**
     * @return $this
     */
    protected function selectDatabase()
    {
        $fn = $this->fnDatabaseSwitcher;
        $fn();
        return $this;
    }
}

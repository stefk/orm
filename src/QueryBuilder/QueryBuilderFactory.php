<?php

namespace Anytime\ORM\QueryBuilder;

use Anytime\ORM\Converter\SnakeToCamelCaseStringConverter;
use Anytime\ORM\EntityManager\Factory;

class QueryBuilderFactory
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var SnakeToCamelCaseStringConverter
     */
    protected $snakeToCamelCaseStringConverter;

    /**
     * @var string
     */
    protected $databaseType;

    /**
     * @var string
     */
    protected $databaseName;

    /**
     * QueryBuilderAbstract constructor.
     * @param \PDO $pdo
     * @param SnakeToCamelCaseStringConverter $snakeToCamelCaseStringConverter
     * @param string $databaseType
     * @param string $databaseName
     */
    public function __construct(\PDO $pdo, SnakeToCamelCaseStringConverter $snakeToCamelCaseStringConverter, string $databaseType, $databaseName)
    {
        $this->snakeToCamelCaseStringConverter = $snakeToCamelCaseStringConverter;
        $this->pdo = $pdo;
        $this->databaseType = $databaseType;
        $this->databaseName = $databaseName;
    }

    /**
     * @return QueryBuilderAbstract
     */
    public function create(): QueryBuilderInterface
    {
        switch($this->databaseType) {
            case Factory::DATABASE_TYPE_MYSQL:
                $qb = new MySqlQueryBuilder($this->pdo, $this->snakeToCamelCaseStringConverter);
                $qb->setDatabaseName($this->databaseName);
                return $qb;
            default:
                throw new \InvalidArgumentException($this->databaseType . ' is not a supported database type');
        }
    }
}

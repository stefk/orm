<?php

namespace Anytime\ORM\QueryBuilder;

use Anytime\ORM\Converter\SnakeToCamelCaseStringConverter;
use Anytime\ORM\EntityManager\DBConnection;
use Anytime\ORM\EntityManager\Factory;

class QueryBuilderFactory
{
    /**
     * @var DBConnection
     */
    protected $DBConnection;

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
     * @param DBConnection $DBConnection
     * @param SnakeToCamelCaseStringConverter $snakeToCamelCaseStringConverter
     * @param string $databaseType
     * @param string $databaseName
     */
    public function __construct(DBConnection $DBConnection, SnakeToCamelCaseStringConverter $snakeToCamelCaseStringConverter, string $databaseType, $databaseName)
    {
        $this->snakeToCamelCaseStringConverter = $snakeToCamelCaseStringConverter;
        $this->DBConnection = $DBConnection;
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
                $qb = new MySqlQueryBuilder($this->DBConnection, $this->snakeToCamelCaseStringConverter);
                $qb->setDatabaseName($this->databaseName);
                return $qb;
            default:
                throw new \InvalidArgumentException($this->databaseType . ' is not a supported database type');
        }
    }
}

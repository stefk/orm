<?php

namespace Anytime\ORM\EntityManager;

use PDO;

class DBConnection
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var callable|null
     */
    private $fnForceDBName;

    /**
     * DBConnection constructor.
     * @param \PDO $pdo
     * @param callable|null $fnForceDBName
     */
    public function __construct(\PDO $pdo, callable $fnForceDBName = null)
    {
        $this->pdo = $pdo;
        $this->fnForceDBName = $fnForceDBName;
    }

    /**
     * @param string $statement
     * @param int $mode
     * @param null $arg3
     * @param array $ctorargs
     * @return bool|\PDOStatement|void
     */
    public function query($statement, $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, $arg3 = null, array $ctorargs = array())
    {
        $this->forceDBName();
        return $this->pdo->query($statement, $mode, $arg3, $ctorargs);
    }

    /**
     * @return bool
     */
    public function beginTransaction()
    {
        $this->forceDBName();
        return $this->pdo->beginTransaction();
    }

    /**
     * @return bool
     */
    public function commit()
    {
        $this->forceDBName();
        return $this->pdo->commit();
    }

    /**
     * @param string $statement
     * @return int
     */
    public function exec($statement)
    {
        $this->forceDBName();
        return $this->pdo->exec($statement);
    }

    /**
     * @param string $statement
     * @param array $driverOptions
     * @return bool|\PDOStatement
     */
    public function prepare($statement, array $driverOptions = array())
    {
        $this->forceDBName();
        return $this->pdo->prepare($statement, $driverOptions);
    }

    /**
     * @return bool
     */
    public function rollBack()
    {
        $this->forceDBName();
        return $this->pdo->rollBack();
    }

    /**
     * @return mixed|void
     */
    public function errorCode()
    {
        return $this->pdo->errorCode();
    }

    /**
     * @return array
     */
    public function errorInfo()
    {
        return $this->pdo->errorInfo();
    }

    /**
     * @param int $attribute
     * @return mixed
     */
    public function getAttribute($attribute)
    {
        return $this->pdo->getAttribute($attribute);
    }

    /**
     * @return bool
     */
    public function inTransaction()
    {
        return $this->pdo->inTransaction();
    }

    /**
     * @param null $name
     * @return string|void
     */
    public function lastInsertId($name = null)
    {
        return $this->pdo->lastInsertId($name);
    }

    /**
     * @param string $string
     * @param int $parameter_type
     * @return string
     */
    public function quote($string, $parameter_type = PDO::PARAM_STR)
    {
        return $this->pdo->quote($string, $parameter_type);
    }

    /**
     * @param int $attribute
     * @param mixed $value
     * @return bool
     */
    public function setAttribute($attribute, $value)
    {
        return $this->pdo->setAttribute($attribute, $value);
    }

    /**
     * @return array
     */
    public static function getAvailableDrivers()
    {
        return \PDO::getAvailableDrivers();
    }

    /**
     * @return mixed
     */
    private function forceDBName()
    {
        if(is_callable($this->fnForceDBName)) {
            $fn = $this->fnForceDBName;
            return $fn($this->pdo);
        }
    }
}

<?php

namespace Anytime\ORM\QueryBuilder;

use Anytime\ORM\EntityManager\DBConnection;

class UpdateQuery extends QueryAbstract implements UpdateQueryInterface
{
    /**
     * UpdateQuery constructor.
     * @param DBConnection $DBConnection
     * @param \PDOStatement $PDOStatement
     * @param $parameters
     * @param array $fieldsToUpdate
     * @param callable $fnDatabaseSwitcher
     */
    public function __construct(DBConnection $DBConnection, \PDOStatement $PDOStatement, $parameters, array $fieldsToUpdate = [], callable $fnDatabaseSwitcher)
    {
        $newFieldsToUpdate = [];

        // This is done to avoid parameters name conflict with the parameters of the where clause
        foreach($fieldsToUpdate as $fieldName => $value) {
            $newFieldsToUpdate['UPDATE_VALUE_'.$fieldName] = $value;
        }

        unset($fieldsToUpdate);

        parent::__construct($DBConnection, $PDOStatement, $parameters + $newFieldsToUpdate, $fnDatabaseSwitcher);
    }

    /**
     * @inheritdoc
     */
    public function execute(): int
    {
        $this->selectDatabase();
        $this->PDOStatement->execute($this->parameters);
        $this->throwPdoError($this->PDOStatement);
        return $this->PDOStatement->rowCount();
    }
}

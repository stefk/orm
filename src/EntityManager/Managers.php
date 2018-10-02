<?php

namespace Anytime\ORM\EntityManager;

class Managers
{
    /**
     * @var Manager[]
     */
    protected $loadedManagers = [];

    /**
     * @var DBConnection
     */
    private $DBConnection;

    /**
     * EntityManager constructor.
     * @param DBConnection $DBConnection
     */
    public function __construct(DBConnection $DBConnection)
    {
        $this->DBConnection = $DBConnection;
    }

    /**
     * @param string $class
     * @param string $defaultClass
     * @param EntityRepository $entityRepository
     * @param EntityManager $entityManager
     * @return Manager
     */
    protected function loadAndGetManager(string $class, string $defaultClass, EntityRepository $entityRepository, EntityManager $entityManager)
    {
        if(array_key_exists($class, $this->loadedManagers)) {
            return $this->loadedManagers[$class];
        }

        if(class_exists($class)) {
            return (new $class($this->DBConnection, $entityRepository, $entityManager));
        } elseif(class_exists($defaultClass)) {
            return (new $defaultClass($this->DBConnection, $entityRepository, $entityManager));
        } else {
            return (new DefaultManager($this->DBConnection, $entityRepository, $entityManager));
        }
    }
}

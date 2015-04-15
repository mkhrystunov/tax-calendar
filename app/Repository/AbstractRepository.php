<?php

namespace Repository;

use Doctrine\DBAL\Driver\Connection;

abstract class AbstractRepository
{
    /** @var Connection */
    protected $dbal;

    public function __construct(Connection $dbal)
    {
        $this->dbal = $dbal;
    }

    public abstract function getAll();
}

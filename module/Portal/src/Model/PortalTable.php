<?php

namespace Portal\Model;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Adapter\Driver\ResultInterface;

abstract class PortalTable
{
    protected Adapter $adapter;
    protected Sql $sql;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->sql = new Sql($this->adapter);
    }

    protected function response(ResultInterface $results = null): array
    {
        if ($results instanceof ResultInterface && $results->isQueryResult()) {
            $returns = (new ResultSet)
                ->initialize($results)
                ->toArray();
        }
        return $returns ?? [];
    }
}

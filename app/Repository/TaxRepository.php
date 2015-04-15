<?php

namespace Repository;

use Model\GroupRange;
use Model\Tax;

class TaxRepository extends AbstractRepository
{
    /**
     * @return Tax[]
     */
    public function getAll()
    {
        $rows = $this->dbal->query(
            <<<SQL
            SELECT
              t.tax_id,
              t.name AS tax_name,
              g.group_id,
              tfg.days,
              tfg.pre_paid,
              r.name AS range_name
            FROM taxes t
            LEFT JOIN taxes_for_groups tfg
              ON tfg.tax_id = t.tax_id
            LEFT JOIN groups g
              ON g.group_id = tfg.group_id
            LEFT JOIN ranges r
              ON r.range_id = tfg.range_id
SQL
        )->fetchAll();

        /** @var Tax[] $taxes */
        $taxes = [];

        foreach ($rows as $row) {
            $taxId = $row['tax_id'];
            $tax = isset($taxes[$taxId]) ? $taxes[$taxId] : new Tax();
            $tax
                ->setId($taxId)
                ->setName($row['tax_name'])
                ->addGroupId($row['group_id'])
                ->setDays($row['days'])
                ->setPrePaid($row['pre_paid'] == 't')
                ->addRange((new GroupRange())
                    ->setRange($row['range_name'])
                    ->setGroupId($row['group_id'])
                );
            $taxes[$taxId] = $tax;
        }

        return $taxes;
    }
}

<?php

namespace Repository;

use Model\Group;

class GroupRepository extends AbstractRepository
{
    /**
     * @return Group[]
     */
    public function getAll()
    {
        $rows = $this->dbal->query(
            <<<SQL
            SELECT
              g.group_id,
              g.name,
              t.tax_id,
              r.report_id
            FROM groups g
              LEFT JOIN taxes_for_groups tfg
                ON tfg.group_id = g.group_id
              LEFT JOIN taxes t
                ON t.tax_id = tfg.tax_id
              LEFT JOIN reports r
                ON r.group_id = g.group_id
            ORDER BY group_id ASC -- only for nice view
SQL
        )->fetchAll();

        /** @var Group[] $groups */
        $groups = [];

        foreach ($rows as $row) {
            $groupId = $row['group_id'];
            $group = isset($groups[$groupId]) ? $groups[$groupId] : new Group();
            $group
                ->setId($groupId)
                ->setName($row['name'])
                ->addReportId($row['report_id'])
                ->addTaxId($row['tax_id']);
            $groups[$groupId] = $group;
        }

        return $groups;
    }
}

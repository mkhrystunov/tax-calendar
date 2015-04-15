<?php

namespace Repository;

use Model\Report;

class ReportRepository extends AbstractRepository
{
    /**
     * @return Report[]
     */
    public function getAll()
    {
        $rows = $this->dbal->query(
            <<<SQL
            SELECT
              r.report_id,
              r.days,
              r.continuing,
              r.group_id,
              ra.name AS range_name
            FROM reports r
            JOIN ranges ra
              ON r.range_id = ra.range_id
SQL
        )->fetchAll();


        /** @var Report[] $reports */
        $reports = [];

        foreach ($rows as $row) {
            $reports[] = $this->fetchReport($row);
        }

        return $reports;
    }

    /**
     * @param array $row
     * @return Report
     */
    private function fetchReport(array $row)
    {
        $report = new Report();
        $report
            ->setId($row['report_id'])
            ->setDays($row['days'])
            ->setContinuing($row['continuing'] == 't')
            ->setRange($row['range_name'])
            ->setGroupId($row['group_id']);
        return $report;
    }
}

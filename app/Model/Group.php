<?php

namespace Model;

class Group
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var int[] */
    private $taxIds = [];
    /** @var int[] */
    private $reportIds = [];

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getTaxIds()
    {
        return $this->taxIds;
    }

    /**
     * @param int[] $taxIds
     * @return $this
     */
    public function setTaxIds(array $taxIds)
    {
        $this->taxIds = $taxIds;
        return $this;
    }

    /**
     * @param int $taxId
     * @return $this
     */
    public function addTaxId($taxId)
    {
        if (!in_array($taxId, $this->taxIds)) {
            $this->taxIds[] = $taxId;
        }
        return $this;
    }

    /**
     * @param int $taxId
     * @return bool
     */
    public function hasTaxId($taxId)
    {
        return in_array($taxId, $this->taxIds);
    }

    /**
     * @return int[]
     */
    public function getReportIds()
    {
        return $this->reportIds;
    }

    /**
     * @param int[] $reportIds
     * @return $this
     */
    public function setReportIds(array $reportIds)
    {
        $this->reportIds = $reportIds;
        return $this;
    }

    /**
     * @param int $reportId
     * @return bool
     */
    public function hasReportId($reportId)
    {
        return in_array($reportId, $this->reportIds);
    }

    /**
     * @param int $reportId
     * @return $this
     */
    public function addReportId($reportId)
    {
        if (!in_array($reportId, $this->reportIds)) {
            $this->reportIds[] = $reportId;
        }
        return $this;
    }
}

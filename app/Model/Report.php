<?php

namespace Model;

class Report
{
    /** @var int */
    private $id;
    /** @var int */
    private $groupId;
    /** @var string */
    private $range;
    /** @var int */
    private $days;
    /** @var boolean */
    private $continuing;

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
     * @return int
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * @param int $groupId
     * @return $this
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
        return $this;
    }

    /**
     * @return string
     */
    public function getRange()
    {
        return $this->range;
    }

    /**
     * @param string $range
     * @return $this
     */
    public function setRange($range)
    {
        $this->range = $range;
        return $this;
    }

    /**
     * @return int
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * @param int $days
     * @return $this
     */
    public function setDays($days)
    {
        $this->days = $days;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isContinuing()
    {
        return $this->continuing;
    }

    /**
     * @param boolean $continuing
     * @return $this
     */
    public function setContinuing($continuing)
    {
        $this->continuing = $continuing;
        return $this;
    }
}

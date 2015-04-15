<?php

namespace Model;

class Tax
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var int[] */
    private $groupIds = [];
    /** @var int */
    private $days;
    /** @var boolean */
    private $prePaid;
    /** @var GroupRange[] */
    private $ranges = [];

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
    public function getGroupIds()
    {
        return $this->groupIds;
    }

    /**
     * @param int[] $groupIds
     * @return $this
     */
    public function setGroups(array $groupIds)
    {
        $this->groupIds = $groupIds;
        return $this;
    }

    /**
     * @param int $groupId
     * @return $this
     */
    public function addGroupId($groupId)
    {
        $this->groupIds[] = $groupId;
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
    public function isPrePaid()
    {
        return $this->prePaid;
    }

    /**
     * @param boolean $prePaid
     * @return $this
     */
    public function setPrePaid($prePaid)
    {
        $this->prePaid = $prePaid;
        return $this;
    }

    /**
     * @return GroupRange[]
     */
    public function getRanges()
    {
        return $this->ranges;
    }

    /**
     * @param int $groupId
     * @return GroupRange
     */
    public function getRangesForGroup($groupId)
    {
        $ranges = [];
        foreach ($this->ranges as $range) {
            if ($range->getGroupId() === $groupId) {
                $ranges[] = $range;
            }
        }
        return $ranges;
    }

    /**
     * @param GroupRange $range
     * @return $this
     */
    public function addRange($range)
    {
        if (!in_array($range, $this->ranges)) {
            $this->ranges[] = $range;
        }
        return $this;
    }
}

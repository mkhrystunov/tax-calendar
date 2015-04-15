<?php

namespace Model;

use Util\DateUtils;

class GroupRange
{
    /** @var int */
    private $groupId;
    /** @var string */
    private $range;

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
        DateUtils::checkPeriod($range);
        $this->range = $range;
        return $this;
    }
}

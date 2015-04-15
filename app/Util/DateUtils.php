<?php

namespace Util;

use DateTime;
use InvalidArgumentException;

class DateUtils
{
    const PERIOD_YEAR = 'year';
    const PERIOD_QUARTER = 'quarter';
    const PERIOD_MONTH = 'month';
    const PERIOD_WEEK = 'week';

    private static $validPeriods = [
        self::PERIOD_YEAR,
        self::PERIOD_QUARTER,
        self::PERIOD_MONTH,
        self::PERIOD_WEEK,
    ];

    /**
     * @param string $period
     * @param DateTime $date
     * @return DateTime
     */
    public static function firstDateOf($period, DateTime $date = null)
    {
        self::checkPeriod($period);

        /** @var DateTime $newDate */
        $newDate = ($date === null) ? new DateTime() : clone $date;

        switch ($period) {
            case DateUtils::PERIOD_YEAR:
                $newDate->modify('first day of january ' . $newDate->format('Y'));
                break;
            case DateUtils::PERIOD_QUARTER:
                $month = $newDate->format('n');
                $year = $newDate->format('Y');
                if ($month < 4) {
                    $newDate->modify('first day of january ' . $year);
                } elseif ($month > 3 && $month < 7) {
                    $newDate->modify('first day of april ' . $year);
                } elseif ($month > 6 && $month < 10) {
                    $newDate->modify('first day of july ' . $year);
                } elseif ($month > 9) {
                    $newDate->modify('first day of october ' . $year);
                }
                break;
            case DateUtils::PERIOD_MONTH:
                $newDate->modify('first day of this month');
                break;
            case DateUtils::PERIOD_WEEK:
                $newDate->modify(($newDate->format('w') === '0') ? 'monday last week' : 'monday this week');
                break;
        }

        return $newDate;
    }

    /**
     * @param string $period
     * @param DateTime $date
     * @return DateTime
     */
    public static function lastDayOf($period, DateTime $date = null)
    {
        self::checkPeriod($period);

        /** @var DateTime $newDate */
        $newDate = ($date === null) ? new DateTime() : clone $date;

        switch ($period) {
            case DateUtils::PERIOD_YEAR:
                $newDate->modify('last day of january ' . $newDate->format('Y'));
                break;
            case DateUtils::PERIOD_QUARTER:
                $month = $newDate->format('n');
                $year = $newDate->format('Y');
                if ($month < 4) {
                    $newDate->modify('last day of january ' . $year);
                } elseif ($month > 3 && $month < 7) {
                    $newDate->modify('last day of april ' . $year);
                } elseif ($month > 6 && $month < 10) {
                    $newDate->modify('last day of july ' . $year);
                } elseif ($month > 9) {
                    $newDate->modify('last day of october ' . $year);
                }
                break;
            case DateUtils::PERIOD_MONTH:
                $newDate->modify('last day of this month');
                break;
            case DateUtils::PERIOD_WEEK:
                $newDate->modify(($newDate->format('w') === '0') ? 'now' : 'sunday this week');
                break;
        }

        return $newDate;
    }

    /**
     * @param $period
     * @param DateTime $start
     * @param DateTime $end
     * @return DateTime[]
     */
    public static function getPeriodsWithinRange($period, DateTime $start, DateTime $end)
    {
        self::checkPeriod($period);
        $step = $period === DateUtils::PERIOD_QUARTER ? '3 month' : '1 ' . $period;
        $cur = $start;
        $periods = [];
        while($cur < $end)
        {
            $firstDate = self::firstDateOf($period, $cur);
            if ($firstDate <= self::lastDayOf($period, $cur)) {
                $periods[] = $firstDate;
            }
            $cur = new DateTime($cur->format('d-m-Y') . '+' . $step);
        }
        return $periods;
    }

    /**
     * @param string $period
     * @throws InvalidArgumentException
     */
    public static function checkPeriod($period)
    {
        $period = strtolower($period);
        if (!in_array($period, self::$validPeriods)) {
            throw new InvalidArgumentException('Period must be one of: ' . implode(', ', self::$validPeriods));
        }
    }
}

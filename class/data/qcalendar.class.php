<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    define(DEFAULT_CALENDAR_FIRST_DAY_OF_WEEK, 0);

    class qCalendar extends qObject
    {
        var $_month;
        var $_year;
        var $_firstDayOfWeek;
        var $_calendar;

        /**
        *    Add function info here
        */
        function qCalendar($month, $year = null, $firstDayOfWeek = DEFAULT_CALENDAR_FIRST_DAY_OF_WEEK)
        {
            $this->qObject();
            $this->_month          = $month;
            $this->_year           = empty($year) ? strftime("%Y"): $year;
            $this->_firstDayOfWeek = $firstDayOfWeek;
            $this->_calendar       = array();

            $this->_generate();
        }

        /**
        *    Add function info here
        */
        function getMonth()
        {
            return $this->_month;
        }

        /**
        *    Add function info here
        */
        function getYear()
        {
            return $this->_year;
        }

        /**
        *    Add function info here
        */
        function getFirstDayOfWeek()
        {
            return $this->_firstDayOfWeek;
        }

        /**
        *    Add function info here
        */
        function setMonth($month)
        {
            $this->_month = $month;
            $this->_generate();
        }

        /**
        *    Add function info here
        */
        function setYear($year)
        {
            $this->_year = $year;
            $this->_generate();
        }

        /**
        *    Add function info here
        */
        function setFirstDayOfWeek($day)
        {
            $this->_firstDayOfWeek = $day;
        }

        /**
        *    Add function info here
        */
        function &getTableOfDays()
        {
            return $this->_calendar;
        }

        /**
        *    Add function info here
        */
        function isToday($day)
        {
            $curDay   = (int) strftime("%d");
            $curMonth = (int) strftime("%m");
            $curYear  = (int) strftime("%Y");

            return ($curMonth == $this->_month && $curYear == $this->_year && $day == $curDay);
        }

        /**
        *    Add function info here
        */
        function _generate()
        {
            $this->_calendar = array();

            $curMonth        = (int) strftime("%m");
            $curYear         = (int) strftime("%Y");
            $weekDay         = (int) strftime("%w", mktime(0, 0, 0, $this->_month, 1, $this->_year));
            $weekDay         = ($weekDay + 7 - $this->_firstDayOfWeek) % 7;
            $dayCount        = 1;

            for ($j = 0; $j < 7; $j++)
            {
                $this->_calendar[0][$j] = ($j + $this->_firstDayOfWeek) % 7;
            }

            for ($i = 1; $i < 7; $i++)
            {
                for ($j = 0; $j < 7; $j++)
                {
                    if (($i == 1 && $j < $weekDay) || !checkdate($this->_month, $dayCount, $this->_year))
                    {
                        $this->_calendar[$i][$j] = false;
                    }
                    else
                    {
                        $this->_calendar[$i][$j] = $dayCount;
                        $dayCount++;
                    }
                }
            }
        }
    }

?>
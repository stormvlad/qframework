<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    define("DEFAULT_CALENDAR_FIRST_DAY_OF_WEEK", 0);

    /**
     * @brief Generación de un calendario
     *
     * Esta clase genera una matriz de valores con los dias de un mes
     * concreto, segun el mes y año especificados.
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 17:18
     * @version 1.0
     * @ingroup data
     */
    class qCalendar extends qObject
    {
        var $_baseUrl;

        var $_year;
        var $_month;
        var $_day;
        
        var $_firstDayOfWeek;
        
        var $_calendar;

        /**
        *    Add function info here
        */
        function qCalendar($baseUrl, $year = null, $month = null, $day = null, $firstDayOfWeek = DEFAULT_CALENDAR_FIRST_DAY_OF_WEEK)
        {
            $this->qObject();

            if (empty($day))
            {
                $day = intVal(strftime("%d"));
            }

            if (empty($month))
            {
                $month = intVal(strftime("%m"));
            }

            if (empty($year))
            {
                $year = intVal(strftime("%Y"));
            }

            $this->_calendar = array();

            $this->_baseUrl  = $baseUrl;
            $this->_date     = $day;
            $this->_month    = $month;
            $this->_year     = $year;

            $this->_firstDayOfWeek = intVal($firstDayOfWeek);
            
            $this->_generate();
        }

        /**
        *    Add function info here
        */
        function getBaseUrl()
        {
            return $this->_baseUrl;
        }
        
        /**
        *    Add function info here
        */
        function getDay($padding = false)
        {
            $day = $this->_day;

            if ($padding)
            {
                $day = sprintf("%02d", $day);
            }

            return $day;
        }

        /**
        *    Add function info here
        */
        function getMonth($padding = false)
        {
            $month = $this->_month;

            if ($padding)
            {
                $month = sprintf("%02d", $month);
            }

            return $month;
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
        function setBaseUrl($url)
        {
            $this->_baseUrl = $url;
        }
        
        /**
        *    Add function info here
        */
        function setDay($day)
        {
            $this->_day = intVal($day);
        }

        /**
        *    Add function info here
        */
        function setMonth($month)
        {
            $this->_month = intVal($month);
            $this->_generate();
        }

        /**
        *    Add function info here
        */
        function setPrevMonth()
        {
            $this->_month--;

            if ($this->_month < 1)
            {
                $this->_month = 12;
                $this->_year--;
            }

            $this->_generate();
        }

        /**
        *    Add function info here
        */
        function setNextMonth()
        {
            $this->_month++;

            if ($this->_month > 12)
            {
                $this->_month = 1;
                $this->_year++;
            }

            $this->_generate();
        }

        /**
        *    Add function info here
        */
        function setYear($year)
        {
            $this->_year = intVal($year);
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
            $curDay   = intVal(strftime("%d"));
            $curMonth = intVal(strftime("%m"));
            $curYear  = intVal(strftime("%Y"));

            return ($curMonth == $this->_month && $curYear == $this->_year && $day == $curDay);
        }

        /**
         *    Add function info here
         * @private
         */
        function _generate()
        {
            $this->_calendar = array();

            $curMonth = intVal(strftime("%m"));
            $curYear  = intVal(strftime("%Y"));
            $weekDay  = intVal(strftime("%w", mktime(0, 0, 0, $this->_month, 1, $this->_year)));
            $weekDay  = ($weekDay + 7 - $this->_firstDayOfWeek) % 7;
            $dayCount = 1;

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

            if (empty($this->_calendar[6][0]))
            {
                unset($this->_calendar[6]);
            }
        }

        /**
        * Add function info here
        */
        function getUrl($month, $day = null)
        {
            $baseUrl = htmlSpecialChars($this->getBaseUrl());
            $month   = sprintf("%02d", $month);
            $year    = $this->getYear();

            if (!empty($day))
            {
                $day = sprintf("%02d", $day);
            }
            
            if (ereg("[?]op=", $baseUrl))
            {
                $url = $baseUrl . "&amp;year=" . $year . "&amp;month=" . $month;

                if (!empty($day))
                {
                    $url .= "&amp;day=" . $day;
                }
            }
            else
            {
                $url = $baseUrl . $year . "/" . $month . "/";

                if (!empty($day))
                {
                    $url .= $day . "/";
                }
            }
            
            return $url;            
        }
        
        /**
        * Add function info here
        */
        function isLink($cell)
        {
            throw(new qException("qCalendar::isLink: This method should have implemented in child classes."));
            die();
        }
    }

?>
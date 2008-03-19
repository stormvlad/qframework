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
        function _getFormattedValue($value, $format = false)
        {
            if (empty($format))
            {
                return $value;
            }

            // For compatibility
            if ($format === true)
            {
                $format = "%02d";
            }
            
            return sprintf($format, $value);
        }
        
        /**
        *    Add function info here
        */
        function getDay($format = false)
        {
            return $this->_getFormattedValue($this->_day, $format);
        }

        /**
        *    Add function info here
        */
        function getMonth($format = false)
        {
            return $this->_getFormattedValue($this->_month, $format);
        }

        /**
        *    Add function info here
        */
        function getPrevMonth($format = false)
        {
            $month = $this->getMonth() - 1;

            if ($month < 1)
            {
                $month = 12;
            }

            return $this->_getFormattedValue($month, $format);
        }

        /**
        *    Add function info here
        */
        function getNextMonth($format = false)
        {
            $month = $this->getMonth() + 1;

            if ($month > 12)
            {
                $month = 1;
            }

            return $this->_getFormattedValue($month, $format);
        }
        
        /**
        *    Add function info here
        */
        function getYear($format = false)
        {
            return $this->_getFormattedValue($this->_year, $format);
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
            $this->_generate();
        }

        /**
        *    Add function info here
        */
        function &getTableOfDays($fillFalses = false, $formatMonths = false)
        {
            $table = $this->_calendar;

            if (!empty($fillFalses))
            {
                if (empty($table[1][0]))
                {
                    $calendar = new Calendar($this->getBaseUrl(), $this->getYear(), $this->getMonth());
                    $calendar->setPrevMonth();
                    $week = $calendar->getLastWeek();
    
                    for ($i = 0; $i < 7; $i++)
                    {
                        if (empty($table[1][$i]))
                        {
                            $table[1][$i] = $week[$i] . "/" . $calendar->getMonth($formatMonths);
                        }
                    }
                }

                $last = count($table) - 1;

                if (empty($table[$last][6]))
                {
                    $calendar = new Calendar($this->getBaseUrl(), $this->getYear(), $this->getMonth());
                    $calendar->setNextMonth();
                    $week = $calendar->getFirstWeek();
    
                    for ($i = 0; $i < 7; $i++)
                    {
                        if (empty($table[$last][$i]))
                        {
                            $table[$last][$i] = $week[$i] . "/" . $calendar->getMonth($formatMonths);
                        }
                    }
                }
            }
            
            return $table;
        }

        /**
        *    Add function info here
        */
        function getWeek($day, $fillFalses = false)
        {
            $weeks = &$this->getTableOfDays($fillFalses);

            for ($i = 1; $i < 6; $i++)
            {
                if (($day <= $weeks[$i][6]) || ($day >= $weeks[$i][0] && $weeks[$i][6] == false))
                {                
                    return $weeks[$i];
                }
            }
            
            return false;
        }

        /**
        *    Add function info here
        */
        function getFirstWeek($fillFalses = false)
        {
            $weeks = &$this->getTableOfDays($fillFalses);
            return $weeks[1];
        }
        
        /**
        *    Add function info here
        */
        function getLastWeek($fillFalses = false)
        {
            $weeks = &$this->getTableOfDays($fillFalses);
            return $weeks[count($weeks) - 1];
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
            $year    = $this->getYear();

            if ($month < 1)
            {
                $month = 12;
                $year--;
            }
            else if ($month > 12)
            {
                $month = 1;
                $year++;
            }
            
            if (!empty($day))
            {
                $day = sprintf("%02d", $day);
            }

            $month = sprintf("%02d", $month);
            
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
        function extractDay($cell)
        {
            return substr($cell, 0, strpos($cell, "/"));
        }
        
        /**
        * Add function info here
        */
        function isFromOtherMonth($cell)
        {
            return strpos($cell, "/") !== false;
        }
        
        /**
        * Add function info here
        */
        function isLink($cell)
        {
            trigger_error("This method must be implemented by child classes.", E_USER_ERROR);
            return false;
        }
    }

?>
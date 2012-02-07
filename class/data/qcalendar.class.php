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
        var $_week;
        
        var $_firstDayOfWeek;
        
        var $_calendar;

        /**
        *    Add function info here
        */
        function qCalendar($baseUrl = null, $year = null, $month = null, $day = null, $firstDayOfWeek = DEFAULT_CALENDAR_FIRST_DAY_OF_WEEK)
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

            $this->_firstDayOfWeek = intVal($firstDayOfWeek);

            $this->_month    = $month;
            $this->_year     = $year;

            $this->setDay($day);
            
            if (empty($baseUrl))
            {
                $server  = &qHttp::getServerVars();
                $baseUrl = $server->getValue("REQUEST_URI");
            }
            
            $this->setBaseUrl($baseUrl);
            
            $this->_generate();
            $this->_autoSetWeek();
        }

        /**
        *    Add function info here
        */
        function _autoSetWeek()
        {
            $this->_week = intVal($this->format("%V"));
            
            if ($this->_week > 52)
            {
                $this->_week = 1;
            }
        }

        /**
        *    Add function info here
        */
        function getLastDay()
        {
            $days = $this->getLastWeekDays();

            for ($i = 6; $i >=0; $i--)
            {
                if (!empty($days[$i]))
                {
                    return $days[$i];
                }
            }

            return false;
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
        function getFormattedValue($value, $format = false)
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
            return $this->getFormattedValue($this->_day, $format);
        }

        /**
        *    Add function info here
        */
        function getWeek()
        {
            return $this->_week;
        }
        
        /**
        *    Add function info here
        */
        function getMonth($format = false)
        {
            return $this->getFormattedValue($this->_month, $format);
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

            return $this->getFormattedValue($month, $format);
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

            return $this->getFormattedValue($month, $format);
        }
        
        /**
        *    Add function info here
        */
        function getYear($format = false)
        {
            return $this->getFormattedValue($this->_year, $format);
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
            $this->_baseUrl = preg_replace("#[/&](year|month|week|day|init|back)[/=][0-9]+#", "", $url);
        }

        /**
        *    Add function info here
        */
        function setDate($year, $month, $day)
        {
            $this->_year  = intVal($year);
            $this->_month = intVal($month);
            $this->_day   = intVal($day);

            $this->_generate();
            $this->_autoSetWeek();
        }
        
        /**
        *    Add function info here
        */
        function setDay($day)
        {
            $this->_day = intVal($day);

            while (!checkdate($this->_month, $this->_day, $this->_year) && $this->_day > 0)
            {
                $this->_day--;
            }
            
            $this->_autoSetWeek();
        }

        /**
        *    Add function info here
        */
        function setPrevDay()
        {
            $this->_day--;

            if ($this->_day < 1)
            {
                $this->setPrevMonth();
            }
            else
            {
                $this->setDay($this->_day);
            }     
        }

        /**
        *    Add function info here
        */
        function setNextDay()
        {
            $this->_day++;

            if ($this->_day > $this->getLastDay())
            {
                $this->setNextMonth();
            }
            else
            {
                $this->setDay($this->_day);
            }
        }
        
        /**
        *    Add function info here
        */
        function setWeek($week)
        {
            include_once(APP_ROOT_PATH . "class/data/date2.class.php");
            
            $days  = (intVal($week) - 1) * 7;
            $year  = $this->getYear();
            $date  = new Date2($year . "-01-01");

            if (!empty($days))
            {
                $date->addDays($days); // esborro el +1 aquest 2012 dóna problemes
            }
            
            $this->setDate($date->getYear(), $date->getMonth(), $date->getDay());
            $days = $this->getWeekDays();

            $this->_day = $days[0];
        }

        /**
        *    Add function info here
        */
        function setPrevWeek()
        {
            $this->_week--;

            if ($this->_week < 1)
            {
                $this->_year--;
                $this->setWeek(52);
            }            
        }

        /**
        *    Add function info here
        */
        function setNextWeek()
        {
            $this->_week++;

            if ($this->_week > 52)
            {
                $this->_year++;
                $this->setWeek(1);
            }
        }
        
        /**
        *    Add function info here
        */
        function setMonth($month)
        {
            $this->_month = intVal($month);

            $this->_generate();

            $this->_day = 1;
            $this->_autoSetWeek();
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

            $this->_day = $this->getLastDay();
            $this->_autoSetWeek();
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

            $this->_day = 1;
            $this->_autoSetWeek();
        }

        /**
        *    Add function info here
        */
        function setYear($year)
        {
            $this->_year = intVal($year);

            $this->_generate();
            $this->_autoSetWeek();
        }

        /**
        *    Add function info here
        */
        function setFirstDayOfWeek($day)
        {
            $this->_firstDayOfWeek = $day;

            $this->_generate();
            $this->_autoSetWeek();
        }

        /**
        *    Add function info here
        */
        function &getTableOfDays($fillFalses = false)
        {
            $table  = $this->_calendar;
            $format = $fillFalses;
            
            if (!empty($format))
            {
                if ($format === true)
                {
                    $format = "%d/%d/%d";
                }
                
                if (empty($table[1][0]))
                {
                    $calendar = new Calendar($this->getBaseUrl(), $this->getYear(), $this->getMonth());
                    $calendar->setPrevMonth();
                    $week = $calendar->getLastWeekDays();
    
                    for ($i = 0; $i < 7; $i++)
                    {
                        if (empty($table[1][$i]))
                        {
                            $table[1][$i] = sprintf($format, $week[$i], $calendar->getMonth(), $calendar->getYear());
                        }
                    }
                }

                $last = count($table) - 1;

                if (empty($table[$last][6]))
                {
                    $calendar = new Calendar($this->getBaseUrl(), $this->getYear(), $this->getMonth());
                    $calendar->setNextMonth();
                    $week = $calendar->getFirstWeekDays();
    
                    for ($i = 0; $i < 7; $i++)
                    {
                        if (empty($table[$last][$i]))
                        {
                            $table[$last][$i] = sprintf($format, $week[$i], $calendar->getMonth(), $calendar->getYear());
                        }
                    }
                }
            }
            
            return $table;
        }

        /**
        *    Add function info here
        */
        function getWeekDays($fillFalses = false, $day = false)
        {
            if (empty($day))
            {
                $day = $this->getDay();
            }
            
            $weeks = &$this->getTableOfDays($fillFalses);

            for ($i = 1; $i < 7; $i++)
            {
                if (($day <= $weeks[$i][6]) ||
                    ($day >= $weeks[$i][0] &&  empty($fillFalses) && empty($weeks[$i][6])) ||
                    ($day >= $weeks[$i][0] && !empty($fillFalses) && $this->isFromOtherMonth($weeks[$i][6]))
                   )
                {
                    return $weeks[$i];
                }
            }
            
            return false;
        }
        
        /**
        *    Add function info here
        */
        function getFirstWeekDays($fillFalses = false)
        {
            $weeks = &$this->getTableOfDays($fillFalses);
            return $weeks[1];
        }
        
        /**
        *    Add function info here
        */
        function getLastWeekDays($fillFalses = false)
        {
            $weeks = &$this->getTableOfDays($fillFalses);
            $ind   = count($weeks) - 1;
            
            if (empty($weeks[$ind][0]))
            {
                $ind--;
            }
            
            return $weeks[$ind];
        }

        /**
        *    Add function info here
        */
        function getLastWeek()
        {
            $weeks = &$this->getTableOfDays();
            return count($weeks) - 1;
        }
        
        /**
        *    Add function info here
        */
        function getStartAndEndDayOfWeek($format, $day = false)
        {
            if (empty($day))
            {
                $day = $this->getDay();
            }
            
            $locale = &Locale::getInstance();
            $week   = $this->getWeekDays(true, $day);
            
            $day1   = $this->extractDay($week[0], "%02d");
            $month1 = $this->extractMonth($week[0], "%02d");
            $year1  = $this->extractYear($week[0]);
            $date1  = $year1 . "-" . $month1 . "-" . $day1;
            
            $day2   = $this->extractDay($week[6], "%02d");
            $month2 = $this->extractMonth($week[6], "%02d");
            $year2  = $this->extractYear($week[6]);
            $date2  = $year2 . "-" . $month2 . "-" . $day2;

            return array
                (
                    $locale->format($format, $date1),
                    $locale->format($format, $date2)
                );
        }
        
        /**
        *    Add function info here
        */
        function isToday($day)
        {
            $curDay   = intVal(strftime("%d"));
            $curMonth = intVal(strftime("%m"));
            $curYear  = intVal(strftime("%Y"));

            return !$this->isFromOtherMonth($day) && ($curMonth == $this->_month && $curYear == $this->_year && $day == $curDay);
        }

        /**
        *    Add function info here
        */
        function isCurrent($cell)
        {
            return $this->isFromOtherMonth($cell) && ($this->extractMonth($cell) == $this->_month && $this->extractYear($cell) == $this->_year && $this->extractDay($cell) == $this->_day);
        }
        
        /**
        *    Add function info here
        */
        function isCurrentMonth($month = null)
        {
            if (empty($month))
            {
                $month = $this->getMonth();
            }
            
            return $month == intVal(strftime("%m"));
        }
        
        /**
        *    Add function info here
        */
        function isCurrentWeek($week = null)
        {
            if (empty($week))
            {
                $week = $this->getWeek();
            }
            
            return $week == intVal($this->format("%V", strftime("%d/%m/%Y")));
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
        function _constructUrl($year, $month, $day = null)
        {
            $baseUrl = htmlSpecialChars($this->getBaseUrl());
            $month   = sprintf("%02d", $month);
            
            if (preg_match("/[?]op=/", $baseUrl))
            {
                $url = $baseUrl . "&amp;year=" . $year . "&amp;month=" . $month;

                if (!empty($day))
                {
                    $url .= "&amp;day=" . sprintf("%02d", $day);
                }
            }
            else
            {
                $url = $baseUrl . $year . "/" . $month . "/";

                if (!empty($day))
                {
                    $url .= sprintf("%02d", $day) . "/";
                }
            }
            
            return $url;       
        }

        /**
        * Add function info here
        */
        function _constructWeekUrl($year, $week)
        {
            $baseUrl = htmlSpecialChars($this->getBaseUrl());
            
            if (preg_match("/[?]op=/", $baseUrl))
            {
                $url = $baseUrl . "&amp;year=" . $year . "&amp;week=" . $week;
            }
            else
            {
                $url = $baseUrl . $year . "/" . $week . "/";
            }
            
            return $url;       
        }
        
        /**
        * Add function info here
        */
        function getUrl($month = null, $day = null)
        {
            if ($month === null)
            {
                $month = $this->getMonth();
            }
            
            if ($day === null)
            {
                $day = $this->getDay();
            }
            
            return $this->_constructUrl($this->getYear(), $month, $day);
        }

        /**
        * Add function info here
        */
        function getMonthUrl($month = null)
        {
            if ($month === null)
            {
                $month = $this->getMonth();
            }
            
            $baseUrl  = $this->getBaseUrl();
            $calendar = new Calendar($baseUrl, $this->getYear(), $this->getMonth());

            if ($month < $this->getMonth())
            {
                $calendar->setPrevMonth();
            }
            else if ($month > $this->getMonth())
            {
                $calendar->setNextMonth();
            }

            return $this->_constructUrl($calendar->getYear(), $calendar->getMonth());
        }
        
        /**
        * Add function info here
        */
        function getWeekUrl($week = null)
        {
            if ($week === null)
            {
                $week = $this->getWeek();
            }

            $baseUrl  = $this->getBaseUrl();
            $calendar = new Calendar($baseUrl, $this->getYear());
            
            $calendar->setWeek($this->getWeek());
            
            if ($week < $this->getWeek())
            {
                $calendar->setPrevWeek();
            }
            else if ($week > $this->getWeek())
            {
                $calendar->setNextWeek();
            }
            
            return $this->_constructWeekUrl($calendar->getYear(), $calendar->getWeek());
        }

        /**
        * Add function info here
        */
        function getDayUrl($day = null)
        {
            if ($day === null)
            {
                $day = $this->getDay();
            }
            
            $baseUrl  = $this->getBaseUrl();
            $calendar = new Calendar($baseUrl, $this->getYear(), $this->getMonth(), $this->getDay());

            if ($day < $this->getDay())
            {
                $calendar->setPrevDay();
            }
            else if ($day > $this->getDay())
            {
                $calendar->setNextDay();
            }

            return $this->_constructUrl($calendar->getYear(), $calendar->getMonth(), $calendar->getDay());
        }
        
        /**
        * Add function info here
        */
        function getTodayUrl()
        {
            $day   = intVal(strftime("%d"));
            $month = intVal(strftime("%m"));
            $year  = intVal(strftime("%Y"));
            
            return $this->_constructUrl($year, $month, $day);
        }
        
        /**
        * Add function info here
        */
        function extractDay($cell, $format = false)
        {
            $pos = strpos($cell, "/");

            if (empty($pos))
            {
                return $this->getFormattedValue($cell, $format);
            }
            
            return $this->getFormattedValue(substr($cell, 0, $pos), $format);
        }

        /**
        * Add function info here
        */
        function extractMonth($cell, $format = false)
        {
            $pos = strpos($cell, "/");

            if ($pos === false)
            {
                $month = $this->getMonth();
            }
            else
            {
                $month = substr($cell, $pos + 1);
            }
            
            return $this->getFormattedValue($month, $format);
        }

        /**
        * Add function info here
        */
        function extractYear($cell, $format = false)
        {
            $pos = strrpos($cell, "/");

            if ($pos === false)
            {
                $year = $this->getYear();
            }
            else
            {
                $year = substr($cell, $pos + 1);
            }
            
            return $this->getFormattedValue($year, $format);
        }
        
        /**
        * Add function info here
        */
        function isFromOtherMonth($cell)
        {
            return strpos($cell, "/") !== false;
        }

        /**
        *    Add function info here
        */
        function format($format, $value = false)
        {
            if (empty($value))
            {
                $value = $this->getDay();
            }

            $locale = &Locale::getInstance();
            $day    = $this->extractDay($value, "%02d");
            $month  = $this->extractMonth($value, "%02d");
            $year   = $this->extractYear($value);
            $date   = $year . "-" . $month . "-" . $day;

            return $locale->format($format, $date);
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
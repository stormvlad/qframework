<?php

     include_once("framework/class/data/Date.class.php");

    class Timestamp extends Date {

        var $_timestamp;

        /*
         * Creates a Timestamp object
         * If $timestamp is empty or not specified, creates a timestamp
         * taking the current time
         */
        function Timestamp($timestamp = null)
        {
            $this->Date($timestamp);
        }

        /**
         * @deprecated Use setDate instead
         */
        function setTime($timestamp, $format = DATE_FORMAT_ISO)
        {
            $this->setDate($timestamp, $format);
        }

        /**
         * @deprecated Use getDate instead
         */
        function getTimestamp($format = DATE_FORMAT_TIMESTAMP)
        {
            return $this->getDate($format);
        }


        /**
          * Returns the ordinal expression for any given number
         * @private
          */
        function _getOrdinal($num)
        {
            // first we check the last two digits
            $last_two_digits = substr($num, -2);

            if ($last_two_digits == "11")
            {
                $value = $num . "th";
            }
            elseif ($last_two_digits == "12")
            {
                $value = $num . "th";
            }
            elseif ($last_two_digits == "13")
            {
                $value = $num . "th";
            }
            else
            {
                $last_digit = substr($num, -1);

                if ($num < 10)
                {
                    $num = $last_digit;
                }

                if ($last_digit == "1")
                {
                    $value = $num . "st";
                }
                elseif ($last_digit == "2")
                {
                    $value = $num . "nd";
                }
                elseif ($last_digit == "3")
                {
                    $value = $num . "rd";
                }
                else
                {
                    $value = $num . "th";
                }
            }

            return $value;
        }

        /**
         * Returns the century corresponding to the given date. If the current year is '2003', then the
         * century will be '2000', not 21.
         *
         * @return An integer value representing the current century.
         */
        function getCentury()
        {
            //return $this->_century;
            throw(new Exception("not implemented?"));
            die();
        }

        /**
         * Returns only the minutes specified by the current date.
         *
         * @return The minutes specified by the current date.
         */
        function getMinutes()
        {
            return $this->getMinute();
        }

        /**
         * Returns only the seconds specified by the current date.
         *
         * @return The seconds specified by the current date.
         */
        function getSeconds()
        {
            return $this->getSecond();
        }

        /**
         * Returns the day in an ordinal format, i.e. 1st, 2nd, 3rd, etc (in English)
         *
         * @return A string with the ordinal representation of the day.
         */
        function getDayOrdinal()
        {
            /*$dayOrdinal = $this->getDay();
            $last_digit = substr($dayOrdinal, -1);

            if ($dayOrdinal < 10)
            {
                $dayOrdinal = $last_digit;
            }

            switch ($this->_locale->getLanguageId())
            {
                case "es":
                case "ca":
                    break;
                case "de":
                case "fi":
                    $dayOrdinal .= ".";
                    break;
                case "en":
                default:
                    $dayOrdinal = $this->_getOrdinal($this->getDay());
                    break;
            }

            return $dayOrdinal;*/
            return $this->getDay();
        }

        /**
          * Returns the name of the month using the current locale.
         *
         * @return A string with the name of the month.
          */
        function getMonthString()
        {
            throw(new Exception("Timestamp::getMonthString not implemented. Use Locale::formatDate instead!"));
            die();
        }

        function getMonth()
        {
            // call the parent getMonth() method
            $month = Date::getMonth();

            if ($month < 10 && $month[0] != "0")
            {
                $month = "0" . $month;
            }

            return $month;
        }

        function setMinutes($newMinutes)
        {
            $this->setMinute($newMinutes);
        }

        /**
         * Instead of returning a string it will just return 0 for sunday, 1 for monday,
         * 2 for tuesday, 3 for wednesday and so on...
         * @deprecated
         * @private
         */
        function getWeekdayId()
        {
            return $this->getDayOfWeek();
        }

        /**
         * @private
         */
        function getNextMonthAndYear()
        {
            if($this->_month == 12)
            {
                $year  = $this->_year + 1;
                $month = "01";
            }
            else
            {
                $year  = $this->_year;
                $month = $this->_month + 1;

                if ($month < 10)
                {
                    $month = "0" . $month;
                }
            }

            return $year . $month;
        }

        /**
         * @private
         */
        function setNextMonthAndYear()
        {
            $result = $this->getNextMonthAndYear();

            $this->setYear(substr($result, 0, 4));
            $this->setMonth(substr($result, 4, 2));

            $this->_calculateFields();
        }

        /**
         * @private
         */
        function getPrevMonthAndYear()
        {
            if ($this->_month == 01)
            {
                $year  = $this->_year-1;
                $month = 12;
            }
            else
            {
                $year  = $this->_year;
                $month = $this->_month - 1;

                if ($month < 10)
                {
                    $month = "0" . $month;
                }
            }

            return $year . $month;
        }

        /**
         * @private
         */
        function setPrevMonthAndYear()
        {
            $result = $this->getPrevMonthAndYear();

            $this->setYear(substr($result, 0, 4));
            $this->setMonth(substr($result, 4, 2));

            $this->_calculateFields();
        }

        /**
         * Returns the UNIX timestamp for the given date.
         *
         * @return An integer specifying the unix timestamp for the given date.
         */
        function getUnixDate()
        {
            return $this->getDate(DATE_FORMAT_UNIXTIME);
        }

        /**
         * Returns the date formatted in ISO 8601
         *
         * @return A string with the date in format ISO 8601
         */
        function getIsoDate()
        {
            return $this->getDate(DATE_FORMAT_ISO);
        }

        /**
         * Static method that returns a timestamp after applying a time
         * difference to it.
         *
         * @static
         * @param timeStamp The original ISO timestamp
         * @param timeDiff The time difference that we'd like to apply to the
         * original timestamp
         */
        function getDateWithOffset($timeStamp, $timeDiff)
        {
            if ($timeDiff != 0)
            {
                $t = new Timestamp($timeStamp);

                //
                // we can't use the addSeconds method with a negative offset
                // so we have to check wether the offset is positive or negative
                // and then use the correct one...
                //
                if ($timeDiff > 0)
                {
                    $t->addSeconds($timeDiff * 3600);
                }
                else
                {
                    $t->subtractSeconds($timeDiff * (-3600));
                }

                $date = $t->getIsoDate();
            }
            else
            {
                $date = $timeStamp;
            }

            return $date;
        }
    }
?>

<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/locale/qlocalefilestorage.class.php");

    define(DEFAULT_LOCALE_CODE, "es_ES");
    define(DEFAULT_LOCALE_FILE_STORAGE, "locale/" . DEFAULT_LOCALE_CODE . ".php");

    /**
     * Extends the Properties class so that our own configuration file is automatically loaded.
     * The configuration file is under config/config.properties.php
     *
     * It is recommented to use this function as a singleton rather than as an object.
     * @see Config
     * @see getConfig
     */
    class qLocale extends qObject
    {
        var $_storage;
        var $_messages;

        /**
        *    Add function info here
        */
        function qLocale(&$storage)
        {
            $this->qObject();

            $this->_storage  = &$storage;
            $this->_messages = new qProperties();

            $this->load();
        }

        /**
        *    Add function info here
        */
        function &getLocale()
        {
            static $localeInstance;

            if (!isset($localeInstance))
            {
                $localeInstance = new qLocale(new qLocaleFileStorage(DEFAULT_LOCALE_FILE_STORAGE));
            }

            return $localeInstance;
        }

        /**
        *    Add function info here
        */
        function getCountryId()
        {
            return substr($this->getLocaleCode(), 3, 2);
        }

        /**
        *    Add function info here
        */
        function getLanguageId()
        {
            return substr($this->getLocaleCode(), 0, 2);
        }

        /**
        *    Add function info here
        */
        function getLocaleCode()
        {
            return $this->getValue("__locale_code__");
        }

        /**
        *    Add function info here
        */
        function getDescription()
        {
            return $this->getValue("__description__");
        }

        /**
        *    Add function info here
        */
        function getCharset()
        {
            return $this->getValue("__charset__");
        }

        /**
        *    Add function info here
        */
        function getDirection()
        {
            return $this->getValue("__direction__");
        }

        /**
        *    Add function info here
        */
        function getDecimalSymbol()
        {
            return $this->getValue("__decimal_symbol__");
        }

        /**
        *    Add function info here
        */
        function getThousandsSeparator()
        {
            return $this->getValue("__thousands_separator__");
        }

        /**
        *    Add function info here
        */
        function getCurrencySymbol()
        {
            return $this->getValue("__currency_symbol__");
        }

        /**
        *    Add function info here
        */
        function getCurrencySymbol2()
        {
            return $this->getValue("__currency_symbol2__");
        }

        /**
        *    Add function info here
        */
        function getCurrencySymbolPosition()
        {
            return $this->getValue("__currency_symbol_position__");
        }

        /**
        *    Add function info here
        */
        function getCurrencyDecimals()
        {
            return $this->getValue("__currency_decimals__");
        }

        /**
        *    Add function info here
        */
        function getTimeFormat()
        {
            return $this->getValue("__time_format__");
        }

        /**
        *    Add function info here
        */
        function getDateFormat()
        {
            return $this->getValue("__date_format__");
        }

        /**
        *    Add function info here
        */
        function getDateFormatShort()
        {
            return $this->getValue("__date_format_short__");
        }

        /**
        *    Add function info here
        */
        function getDateTimeFormat()
        {
            return $this->getValue("__date_time_format__");
        }

        /**
        *    Add function info here
        */
        function getFirstDayOfWeek()
        {
            return $this->getValue("__first_day_of_week__");
        }

        /**
        *    Add function info here
        */
        function getPaperFormat()
        {
            return $this->getValue("__paper_format__");
        }

        /**
        *    Add function info here
        */
        function setCode($code)
        {
            $this->setValue("__locale_code__", $code);
        }

        /**
        *    Add function info here
        */
        function setDescription($description)
        {
            $this->setValue("__description__", $description);
        }

        /**
        *    Add function info here
        */
        function setCharset($charset)
        {
            $this->setValue("__charset__", $charset);
        }

        /**
        *    Add function info here
        */
        function setDirection($direction)
        {
            $this->setValue("__direction__", $direction);
        }

        /**
        *    Add function info here
        */
        function setDecimalSymbol($symbol)
        {
            $this->setValue("__decimal_symbol__", $symbol);
        }

        /**
        *    Add function info here
        */
        function setThousandsSeparator($separator)
        {
            $this->setValue("__thousands_separator__", $separator);
        }

        /**
        *    Add function info here
        */
        function setCurrencySymbol($symbol)
        {
            $this->setValue("__currency_symbol__", $symbol);
        }

        /**
        *    Add function info here
        */
        function setCurrencySymbol2($symbol)
        {
            $this->setValue("__currency_symbol2__", $symbol);
        }

        /**
        *    Add function info here
        */
        function setCurrencySymbolPosition($position)
        {
            $this->setValue("__currency_symbol_position__", $position);
        }

        /**
        *    Add function info here
        */
        function setCurrencyDecimals($num)
        {
            $this->setValue("__currency_decimals__", $num);
        }

        /**
        *    Add function info here
        */
        function setTimeFormat($format)
        {
            $this->setValue("__time_format__", $format);
        }

        /**
        *    Add function info here
        */
        function setDateFormat($format)
        {
            $this->setValue("__date_format__", $format);
        }

        /**
        *    Add function info here
        */
        function setDateFormatShort($format)
        {
            $this->setValue("__date_format_short__", $format);
        }

        /**
        *    Add function info here
        */
        function setDateTimeFormat($format)
        {
            $this->setValue("__date_time_format__", $format);
        }

        /**
        *    Add function info here
        */
        function setFirstDayOfWeek($day)
        {
            $this->setValue("__first_day_of_week__", $day);
        }

        /**
        *    Add function info here
        */
        function setPaperFormat($format)
        {
            $this->setValue("__paper_format__", $format);
        }

        /**
        *    Add function info here
        */
        function load()
        {
            return $this->_storage->load($this);
        }

        /**
        *    Add function info here
        */
        function saveValue($name, $value)
        {
            return $this->_storage->saveValue($this, $name, $value);
        }

        /**
        *    Add function info here
        */
        function save()
        {
            return $this->_storage->save($this);
        }

        /**
        *    Add function info here
        */
        function i18n($id)
        {
            if ($this->isDebug())
            {
                return $id;
            }

            if (is_array($id))
            {
                $translated = array();

                foreach ($id as $key => $value)
                {
                    $tKey   = $this->i18n($key);
                    $tValue = $this->i18n($value);

                    $translated[$tKey] = $tValue;
                }
            }
            else
            {
                if ($this->keyExists($id))
                {
                    $translated = $this->_messages->getValue($id);
                }
                else
                {
                    $translated = $id;
                }

                if( $this->getDirection() == "rtl" )
                {
                    $translated = "<span dir=\"rtl\">" . $translated . "</span>";
                }

                $numArgs = func_num_args();
                $argList = func_get_args();

                for ($i = 1; $i < $numArgs; $i++)
                {
                    $translated = str_replace("%" . $i, $argList[$i], $translated);
                }
            }

            return $translated;
        }

        /**
        *    Add function info here
        */
        function getValue($key)
        {
            return $this->_messages->getValue($key);
        }

        /**
        *    Add function info here
        */
        function setValues($values)
        {
            return $this->_messages->setValues($values);
        }

        /**
        *    Add function info here
        */
        function setValue($key, $value)
        {
            return $this->_messages->setValue($key, $value);
        }

        /**
        *    Add function info here
        */
        function getKeys()
        {
            return $this->_messages->getKeys();
        }

        /**
        *    Add function info here
        */
        function getValues()
        {
            return $this->_messages->getValues();
        }

        /**
        *    Add function info here
        */
        function getAsArray()
        {
            return $this->_messages->getAsArray();
        }

        /**
        *    Add function info here
        */
        function keyExists($key)
        {
            return $this->_messages->keyExists($key);
        }

        /**
        *    Add function info here
        */
        function formatNumber($number, $decimals = null)
        {
            if (empty($decimals))
            {
                $decimals = is_float($number) ? $this->getCurrencyDecimals() : 0;
            }
            return  number_format($number, $decimals, $this->getDecimalSymbol(), $this->getThousandsSeparator());
        }

        /**
        *    Add function info here
        */
        function formatCurrency($number, $html = true)
        {
            $symbol = $html ? $this->getCurrencySymbol() : $this->getCurrencySymbol2();
            $result = number_format($number, $this->getCurrencyDecimals(), $this->getDecimalSymbol(), $this->getThousandsSeparator());

            switch (strtoupper($this->getCurrencySymbolPosition()))
            {
                case "L":
                    $result = $symbol . $result;
                    break;

                case "R":

                default:
                    $result = $result . $symbol;
            }

            return $result;
        }

        /**
        *    Add function info here
        */
        function formatTime($timeStamp = null)
        {
            return $this->format($this->getTimeFormat(), $timeStamp);
        }

        /**
        *    Add function info here
        */
        function formatDate($timeStamp = null)
        {
            return $this->format($this->getDateFormat(), $timeStamp);
        }

        /**
        *    Add function info here
        */
        function formatDateShort($timeStamp = null)
        {
            return $this->format($this->getDateFormatShort(), $timeStamp);
        }

        /**
        *    Add function info here
        */
        function formatDateTime($timeStamp = null)
        {
            return $this->format($this->getDateTimeFormat(), $timeStamp);
        }

        /**
        *    Add function info here
        */
        function format($format, $timeStamp = null)
        {
            if (empty($timeStamp))
            {
                $timeStamp = mktime();
            }
            else
            {
                $timeStamp = mktime(substr($timeStamp, 8, 2), substr($timeStamp, 10, 2), substr($timeStamp, 12, 2), substr($timeStamp, 4, 2), substr($timeStamp, 6, 2), substr($timeStamp, 0, 4));
            }

            $hour        = (int) strftime("%H", $timeStamp);
            $hour2       = (int) strftime("%I", $timeStamp);
            $minute      = (int) strftime("%M", $timeStamp);
            $second      = (int) strftime("%S", $timeStamp);

            $day         = (int) strftime("%d", $timeStamp);
            $month       = (int) strftime("%m", $timeStamp);
            $week        = (int) strftime("%V", $timeStamp);
            $week2       = (int) strftime("%W", $timeStamp);
            $week3       = (int) strftime("%U", $timeStamp);
            $year        = (int) strftime("%Y", $timeStamp);
            $year2       = (int) strftime("%y", $timeStamp);
            $century     = (int) ($year / 100);

            $lTime       = localtime($timeStamp, true);
            $offset      = str_replace("00", ":00", strftime("%z", $timeStamp));

            if ($lTime["tm_isdst"])
            {
                $tmp    = intVal(substr($offset, 1, 2)) - 1;
                $tmp    = sprintf("%02s", $tmp);
                $offset = ereg_replace("([+-])([0-9]{2}):([0-9]{2})", "\\1" . $tmp . ":\\3", $offset);
            }

            $offset2     = $offset;

            if (substr($offset, 1, 2) == "00")
            {
                $offset2 = "Z";
            }

            $timeZone    = strftime("%Z", $timeStamp);

            $rTime       = strftime("%r", $timeStamp);
            $rTimeR      = strftime("%R", $timeStamp);
            $amPm        = strftime("%p", $timeStamp);

            $weekDayNum  = (int) strftime("%w", $timeStamp);
            $weekDayNum2 = (int) strftime("%u", $timeStamp);

            $yearDayNum  = (int) strftime("%j", $timeStamp);

            $days        = $this->i18n("_days");
            $daysShort   = $this->i18n("_days_short");
            $months      = $this->i18n("_months");
            $monthsShort = $this->i18n("_months_short");

            $result      = $format;
            $result      = str_replace("%w3c", sprintf("%s-%02s-%02sT%02s:%02s.%02s%s", $year, $month, $day, $hour, $minute, $second, $offset2), $result);
            $result      = str_replace("%a", $daysShort[$weekDayNum], $result);
            $result      = str_replace("%A", $days[$weekDayNum], $result);
            $result      = str_replace("%b", $monthsShort[$month - 1], $result);
            $result      = str_replace("%B", $months[$month - 1], $result);
            $result      = str_replace("%c", sprintf("%s %s %s %s %02s:%02s:%02s %s", $daysShort[$weekDayNum], $day, $months[$month - 1], $year, $hour, $minute, $second, $timeZone), $result);
            $result      = str_replace("%C", sprintf("%02s", $century), $result);
            $result      = str_replace("%d", sprintf("%02s", $day), $result);
            $result      = str_replace("%D", sprintf("%02s/%02s/%02s", $month, $day, $year2), $result);
            $result      = str_replace("%e", $day, $result);
            $result      = str_replace("%g", substr($year, 2, 2), $result);
            $result      = str_replace("%G", $year, $result);
            $result      = str_replace("%h", $monthsShort[$month - 1], $result);
            $result      = str_replace("%H", sprintf("%02s", $hour), $result);
            $result      = str_replace("%I", $hour2, $result);
            $result      = str_replace("%j", sprintf("%03s", $yearDayNum), $result);
            $result      = str_replace("%m", sprintf("%02s", $month), $result);
            $result      = str_replace("%M", sprintf("%02s", $minute), $result);
            $result      = str_replace("%n", "\n", $result);
            $result      = str_replace("%p", $amPm, $result);
            $result      = str_replace("%r", $rTime, $result);
            $result      = str_replace("%R", $rTimeR, $result);
            $result      = str_replace("%S", sprintf("%02s", $second), $result);
            $result      = str_replace("%t", "\t", $result);
            $result      = str_replace("%T", sprintf("%02s:%02s:%02s", $hour, $minute, $second), $result);
            $result      = str_replace("%u", $weekDayNum2, $result);
            $result      = str_replace("%U", $week3, $result);
            $result      = str_replace("%V", $week, $result);
            $result      = str_replace("%W", $week2, $result);
            $result      = str_replace("%w", $weekDayNum, $result);
            $result      = str_replace("%x", sprintf("%02s/%02s/%02s", $month, $day, $year2), $result);
            $result      = str_replace("%X", sprintf("%02s:%02s:%02s", $hour, $minute, $second), $result);
            $result      = str_replace("%y", substr($year, 2, 2), $result);
            $result      = str_replace("%Y", $year, $result);
            $result      = str_replace("%z", $offset, $result);
            $result      = str_replace("%Z", $timeZone, $result);
            $result      = str_replace("%%", "%", $result);

            return $result;
        }
    }
?>
<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qtimestamp.class.php");

    define(DEFAULT_LOCALE_CODE, "es_ES");
    define(DEFAULT_LOCALE_FOLDER, "locale");
    define(DEFAULT_ENCODING, "iso-8859-1");

    /**
     * Class used to localize messages and things such as dates and numbers.
     *
     * To use this class, we will have to provide a file containing an array
     * of the form:
     *
     * <pre>
     * $messages["identifier"] = "Translated text"
     * </pre>
     *
     * The file will be loaded when creating this object and must be called following
     * the same scheme: locale_lang_COUNTRY (see constructor on locales namig schemes)
     *
     * When we want to translate a string, we will have to use its identifier, that will
     * be looked up in the array containing all the messages. If there is a message for that
     * identifier, it will be returned or a empty string otherwise.
     *
     * This class is extensively used throughout the templates to localize texts, dates
     * and numbers, being the formatDate function one of the most importants of this class.
     *
     * <b>IMPORTANT:</b> For performance reasons, it is recommended to use the Locales::getLocale
     * method instead of creating new Locale objects every time we need one. The getLocale methods
     * offers caching capabilities so that the file with the messages will not need to be fetched
     * every time from disk.
     * @see Locales::getLocale()
     */
    class qLocale extends qObject
    {
        var $_code;
        var $_defaultFolder;
        var $_messages;
        var $_charset;

        /**
         * Constructor.
         *
         * @param $code Code follows the Java naming scheme: language_COUNTRY, so
         * for example if we want to have the texts translated in the English spoken
         * in the UK, we'd have to use en_UK as the code. The two digits country
         * code and language code are ISO standards and can be found in
         * http://userpage.chemie.fu-berlin.de/diverse/doc/ISO_3166.html (country codes) and
         * http://userpage.chemie.fu-berlin.de/diverse/doc/ISO_639.html (language codes)
         */
        function qLocale($code = DEFAULT_LOCALE_CODE)
        {
            $this->qObject();

            $this->_code          = $code;
            $this->_charset       = $this->_messages["encoding"];
            $this->_direction     = $this->_messages["direction"];
            $this->_defaultFolder = DEFAULT_LOCALE_FOLDER;

            $this->_loadLocaleFile();

            if (empty($this->_charset))
            {
                $this->_charset = DEFAULT_ENCODING;
            }
        }

        /**
         * @private
         */
        function _loadLocaleFile()
        {
            $fileName = $this->_defaultFolder . "/locale_" . $this->_code . ".php";

            include($fileName);
            $this->_messages = $messages;
        }

        function getDefaultFolder()
        {
            return $this->_defaultFolder;
        }

        /**
         * Returns the character encoding method used by the current locale file. It has to be a valid
         * character encoding, since it will be used in the header of the html file to tell the browser
         * which is the most suitable encoding that should be used.
         *
         * @return A valid character encoding method.
         */
        function getCharset()
        {
            return $this->_charset;
        }

        /**
         * returns the direction in which this language is written.
         * Possible values are, as with the html standard, "rtl" or "ltr"
         *
         */
        function getDirection()
        {
            $direction = $this->_direction;

            if ($direction != "rtl")
            {
                $direction = "ltr";
            }

            return $direction;
        }

        /**
         * Returns an optional locale description string that can be included in the
         * locale file with the other texts.
         *
         * @return A string describing the locale file.
         */
        function getDescription()
        {
            return $this->_messages["locale_description"];
        }

        /**
         * Changes the locale to something else than what we chose in the first place when
         * creating the object.
         *
         * @param code follows the same format as in the constructor.
         */
        function setLocale($code)
        {
            $this->_code = $code;
            $this->_loadLocaleFile();
        }

        /**
         * Translates a string
         *
         * @param id Identifier of the message we would like to translate
         */
        function getString($id, $default = -1)
        {
            if ($default == -1)
            {
                $default = $id;
            }

            $string = $this->_messages[$id];

            if ($string == "")
            {
                $string = $default;
            }

            if ($this->_direction == "rtl")
            {
                $string = "<span dir=\"rtl\">$string</span>";
            }

            return $string;
        }

        /**
         * Alias for getString
         * @see getString
         */
        function tr($id, $default = -1)
        {
            return $this->getString($id, $default);
        }

        /**
         * Alias for getString
         * @see getString
         */
        function i18n($id, $default = -1)
        {
            return $this->getString($id, $default);
        }

        /**
         * calls printf on the translated string.
         *
         * Crappy Crappy! Since it only accepts two arguments... ;) Well, if we
         * ever need more than two, I'll change it!
         * @private
         */
        function pr($id, $arg1 = null, $arg2 = null)
        {
            $str = $this->tr( $id );

            if (empty($arg1))
            {
                $result = $str;
            }

            if (empty($arg2))
            {
                $result = sprintf($str, $arg1);
            }
            else
            {
                $result = sprintf($str, $arg1, $arg2);
            }

            return $result;
        }

        /**
         * Returns the complete code
         *
         * @return The Locale code
         */
        function getLocale()
        {
            return $this->_code;
        }

        /**
         * Returns the two-character language code
         *
         * @return The two-character language code
         */
        function getLanguageId()
        {
            return substr($this->_code, 0, 2);
        }

        /**
         * Returns the two-character country code
         *
         * @return The two-character country code.
         */
        function getCountryId()
        {
            return substr($this->_code, 3, 5);
        }

        /**
         * Returns the first day of the week, which also depends on the country
         *
         * @return Returns 0 for Sunday, 1 for Monday and so on...
         */
        function firstDayOfWeek()
        {
            switch ($this->getCountryId())
            {
                case "US":
                case "AU":
                case "IE":
                case "UK":
                    $day = 0;
                    break;

                default:
                    $day = 1;
                    break;
            }

            return $day;
        }

        /**
         * Returns all the months of the year
         *
         * @return Returns an array containing the names of the months, where the
         * first one is January.
         */
        function getMonthNames()
        {
            return $this->_messages["months"];
        }

        /**
         * Returns the days of the week
         *
         * @return Returns the names of the days of the week, where the first one is
         * Sunday.
         */
        function getDayNames()
        {
            return $this->_messages["days"];
        }

        /**
         * Returns the shorter version of the days of the week
         *
         * @return Returns an array with the days of the week abbreviated, where the first
         * one is Sunday.
         */
        function getDayNamesShort()
        {
            return $this->_messages["daysshort"];
        }

        /**
         * Formats the date of a Timestamp object according to the given format:
         *
         * (compatible with PHP):<ul>
         * <li>%a abbreviated weekday</li>
         * <li>%A    complete weekday</li>
         * <li>%b    abbreviated month</li>
         * <li>%B    long month</li>
         * <li>%d    day of the month, numeric</li>
         * <li>%H    hours, in 24-h format</li>
         * <li>%I    hours, in 12-h format</li>
         * <li>%M    minutes</li>
         * <li>%m    month number, from 00 to 12</li>
         * <li>%S    seconds</li>
         * <li>%y    2-digit year representation</li>
         * <li>%Y    4-digit year representation</li>
         * <li>%%    the '%' character
         * </ul>
         * (these have been added by myself and are therefore incompatible with php)<ul>
         * <li>%T    "_day_ of _month_", where the day is in ordinal form and 'month' is the name of the month</li>
         * <li>%D    cardinal representation of the day</li>
         * </ul>
         */
        function formatDate($format, $timeStamp = null)
        {
            if (empty($timeStamp))
            {
                $timeStamp = new qTimeStamp();
            }

            $monthId    = (int)$timeStamp->getMonth();
            $monthStr   = $this->_messages["months"][$monthId-1];
            $weekdayId  = $timeStamp->getWeekdayId();
            $weekday    = $this->_messages["days"][$weekdayId];

            $values["%a"] = substr($weekday, 0, 2);
            $values["%A"] = $weekday;
            $values["%b"] = substr($monthStr, 0, 3);
            $values["%B"] = $monthStr;
            $values["%d"] = $timeStamp->getDay();
            $values["%H"] = $timeStamp->getHour();
            $values["%I"] = $timeStamp->getHour / 2;
            $values["%M"] = $timeStamp->getMinutes();
            $values["%m"] = $timeStamp->getMonth();
            $values["%S"] = $timeStamp->getSeconds();
            $values["%y"] = substr($timeStamp->getYear(), 2, 4);
            $values["%Y"] = $timeStamp->getYear();
            $values["%%"] = "%";
            $values["%T"] = $timeStamp->getDayOrdinal() . " " . $this->tr("of") . " " . $monthStr;
            $values["%D"] = $timeStamp->getDayOrdinal();

            $text = $format;

            foreach(array_keys($values) as $key)
            {
                $text = str_replace($key, $values[$key], $text);
            }

            return $text;
        }
    }
?>

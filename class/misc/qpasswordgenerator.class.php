<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/misc/qutils.class.php");

    define(DEFAULT_PASSWORD_GENERATOR_VALID_CHARS, "abcdefghijklmnopqrstuvwxyz0123456789");
    define(DEFAULT_PASSWORD_GENERATOR_MIN_LENGTH, 6);
    define(DEFAULT_PASSWORD_GENERATOR_MAX_LENGTH, 0);

    /**
     * Operating system detection functions. This class provides a bunch of functions in order to detect
     * on which operating system our php parser is running. Please bear in mind that this has not been
     * thoroughly tested and that at the moment it only provides detection for windows and linux.
     */
    class qPasswordGenerator extends qObject
    {
        /**
         * Returns the OS string returned by php_uname
         *
         * @return The OS string.
         * @static
         */
        function qPasswordGenerator()
        {
        }

        /**
        *    Add function info here
        */
        function generate($validChars = DEFAULT_PASSWORD_GENERATOR_VALID_CHARS, $minLength = DEFAULT_PASSWORD_GENERATOR_MIN_LENGTH, $maxLength = DEFAULT_PASSWORD_GENERATOR_MAX_LENGTH)
        {
            if ($maxLength < $minLength)
            {
                $low  = $minLength;
                $high = $minLength;
            }
            else
            {
                $low  = $minLength;
                $high = $maxLength;
            }

            $numbers   = range($low, $high);
            $length    = $numbers[array_rand($numbers)];
            $chars     = qUtils::explode("", $validChars);
            $selChars  = array_rand($chars, $length);
            $password  = "";

            foreach ($selChars as $ind)
            {
                $password .= $chars[$ind];
            }

            return $password;
        }
    }
?>
<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/misc/qutils.class.php");

    define("DEFAULT_PASSWORD_GENERATOR_VALID_CHARS", "abcdefghijklmnopqrstuvwxyz0123456789");
    define("DEFAULT_PASSWORD_GENERATOR_MIN_LENGTH", 6);
    define("DEFAULT_PASSWORD_GENERATOR_MAX_LENGTH", 0);

    /**
     * @brief Generación de contraseñas
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 17:55
     * @version 1.0
     * @ingroup misc
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
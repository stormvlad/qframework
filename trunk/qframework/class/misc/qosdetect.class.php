<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Operating system detection functions. This class provides a bunch of functions in order to detect
     * on which operating system our php parser is running. Please bear in mind that this has not been
     * thoroughly tested and that at the moment it only provides detection for windows and linux.
     */
    class qOsDetect extends qObject
    {
        /**
         * Returns the OS string returned by php_uname
         *
         * @return The OS string.
         * @static
         */
        function getOsString()
        {
            return php_uname();
        }

        /**
         * Returns true if we are running windows.
         *
         * @return True if we are running windows, false otherwise.
         * @static
         */
        function isWindows()
        {
            return eregi("win", qOsDetect::getOsString());
        }

        /**
         * Returns true if we are running Linux.
         *
         * @return True if we are running Linux, false otherwise.
         * @static
         */
        function isLinux()
        {
            return eregi("linux", qOsDetect::getOsString());
        }


         /**
         * Returns true if we are running Mac OS X.
         *
         * @return True if we are running Mac OS X, false otherwise.
         * @static
         */
        function isMacOsX()
        {
            return eregi("mac", qOsDetect::getOsString());
        }
    }
?>

<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Detección del Sistema Operativo
     *
     * Proporciona funciones para detectar el operatio en el cual el sistema se
     * esta ejecutando. Actualmente sólo se implementa para Windows y Linux.
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 17:54
     * @version 1.0
     * @ingroup misc
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

<?php

    include_once("qframework/class/object/qobject.class.php" );

    define( "PLOG_PROJECT_PAGE", "http://www.plogworld.org" );

    if (!defined("DEFAULT_VERSION_FILE")) {
        define( DEFAULT_VERSION_FILE, PLOG_CLASS_PATH . "version.php" );
    }

    /**
     * Returns the version of pLog we're running.
     */
    class qVersion extends qObject {

        /**
         * Returns the current version of pLog, determined by the value of the $version
         * variable in the version.php file.
         * If the file is not available, the result is unknown.
         * @static
         * @return The version identifier.
         */
        function getVersion()
        {
            include_once("version.php" );

            return $version;
        }

        /**
         * Returns the official page of the project.
         *
         * @return The official project page.
         */
        function getProjectPage()
        {
            return PLOG_PROJECT_PAGE;
        }
    }
?>

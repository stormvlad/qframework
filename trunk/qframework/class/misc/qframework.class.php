<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    define("QFRAMEWORK_VERSION", "0.9b");

    class qFramework extends qObject
    {
        /**
         * Add function info here
         */
        function getVersion()
        {
            return QFRAMEWORK_VERSION;
        }
    }
?>

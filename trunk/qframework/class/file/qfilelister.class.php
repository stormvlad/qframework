<?php

     include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Encapsulation of a class to manage files. It is basically a wrapper
     * to some of the php functions.
     * http://www.php.net/manual/en/ref.filesystem.php
     */
     class qFileLister extends qObject
     {
        /**
        *    Add function info here
        */
        function qFileLister()
        {
            $this->qObject();
        }

        /**
        *  Add function info here
        */
        function ls($dir = null)
        {
            throw(new qException("qFileLister::ls: This method must be implemented by child classes."));
            die();
        }
     }
?>

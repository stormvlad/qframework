<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qUserStorage extends qObject
    {
        /**
        * Add function info here
        */
        function qUserStorage()
        {
            $this->qObject();
        }

        /**
        * Add function info here
        */
        function load(&$user)
        {
            throw(new Exception("qUserStorage::load: This method must be implemented by child classes."));
            die();
        }

        /**
        * Add function info here
        */
        function store(&$user)
        {
            throw(new Exception("qUserStorage::store: This method must be implemented by child classes."));
            die();
        }
    }

?>
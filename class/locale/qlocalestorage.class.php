<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
    * Interface class that defines the methods that should be implemented
    * by child classes wishing to implement a configuratino settings storage backend.
    */
    class qLocaleStorage extends qObject
    {
        /**
        * Add function info here
        */
        function qLocaleStorage()
        {
            $this->qObject();
        }

        /**
        * Add function info here
        */
        function load(&$config)
        {
            throw(new qException("qLocaleStorage::load: This method must be implemented by child classes."));
            die();
        }

        /**
        * Add function info here
        */
        function saveValue(&$config, $name, $value)
        {
            throw(new qException("qLocaleStorage::saveValue: This method must be implemented by child classes."));
            die();
        }

        /**
        * Add function info here
        */
        function save(&$config)
        {
            throw(new qException("qLocaleStorage::save: This method must be implemented by child classes."));
            die();
        }
    }
?>

<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qUser extends qObject
    {
        var $_sid;
        var $_storage;
        var $_authenticated;
        var $_attributes;

        /**
        * Add function info here
        */
        function qUser($sid, &$storage)
        {
            $this->qObject();

            $this->_sid           = $sid;
            $this->_storage       = &$storage;
            $this->_authenticated = false;
            $this->_attributes    = new qProperties();
        }

        /**
        * Add function info here
        */
        function getSid()
        {
            return $this->_sid;
        }

        /**
        * Add function info here
        */
        function setSid($sid)
        {
            $this->_sid = $sid;
        }

        /**
        * Add function info here
        */
        function isAuthenticated()
        {
            return $this->_authenticated;
        }

        /**
        * Add function info here
        */
        function setAuthenticated($auth = true)
        {
            $this->_authenticated = $auth;
        }

        /**
        * Add function info here
        */
        function &getAttributes()
        {
            return $this->_attributes->getAsArray();
        }

        /**
        * Add function info here
        */
        function getAttribute($name)
        {
            return $this->_attributes->getValue($name);
        }

        /**
        * Add function info here
        */
        function setAttributes($attributes)
        {
            foreach ($attributes as $name => $value)
            {
                $this->_attributes->setValue($name, $value);
            }
        }

        /**
        * Add function info here
        */
        function setAttribute($name, $value)
        {
            $this->_attributes->setValue($name, $value);
        }

        /**
        * Add function info here
        */
        function hasAttribute($name)
        {
            return $this->_attributes->keyExists($name);
        }

        /**
        * Add function info here
        */
        function load()
        {
            $this->_attributes->reset();
            $this->_storage->load($this);
        }

        /**
        * Add function info here
        */
        function store()
        {
            $this->_storage->store($this);
        }
    }
?>

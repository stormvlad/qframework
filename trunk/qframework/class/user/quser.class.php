<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/user/qusersessionstorage.class.php");

    define(DEFAULT_USER_PERMISSIONS_LEVEL, "__all__");

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
        var $_permissions;

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
            $this->_permissions   = array();
        }

        /**
        * Add function info here
        */
        function &getUser()
        {
            static $user;

            if (!isset($user))
            {
                session_start();
                $user = new qUser(session_id(), new qUserSessionStorage());
            }

            return $user;
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
        function &getPermissions()
        {
            return $this->_permissions;
        }

        /**
        * Add function info here
        */
        function setPermissions(&$permissions)
        {
            $this->_permissions = &$permissions;
        }

        /**
        * Add function info here
        */
        function setPermission($name, $level = DEFAULT_USER_PERMISSIONS_LEVEL)
        {
            $this->_permissions[$level][$name] = true;
        }

        /**
        * Add function info here
        */
        function resetPermissions()
        {
            $this->_permissions = array();
        }

        /**
        * Add function info here
        */
        function removePermission($name, $level = DEFAULT_USER_PERMISSIONS_LEVEL)
        {
            unset($this->_permissions[$level][$name]);
        }

        /**
        * Add function info here
        */
        function hasPermission($name, $level = DEFAULT_USER_PERMISSIONS_LEVEL)
        {
            return is_array($this->_permissions[$level]) && array_key_exists($name, $this->_permissions[$level]);
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

<?php

     include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Encapsulation of a class to manage files. It is basically a wrapper
     * to some of the php functions.
     * http://www.php.net/manual/en/ref.filesystem.php
     */
     class qFileListEntry extends qObject
     {
        var $_name;
        var $_size;
        var $_permissions;
        var $_user;
        var $_group;
        var $_timeStamp;
        var $_isDir;

        /**
        *    Add function info here
        */
        function qFileListEntry($name, $size, $permissions, $user, $group, $timeStamp, $isDir = false)
        {
            $this->qObject();

            $this->_name        = $name;
            $this->_size        = $size;
            $this->_permissions = $permissions;
            $this->_user        = $user;
            $this->_group       = $group;
            $this->_timeStamp   = $timeStamp;
            $this->_isDir       = $isDir;
        }

        /**
         * Add function info here
         */
        function getName()
        {
            return $this->_name;
        }

        /**
         * Add function info here
         */
        function getSize()
        {
            return $this->_size;
        }

        /**
         * Add function info here
         */
        function getPermissions()
        {
            return $this->_permissions;
        }

        /**
         * Add function info here
         */
        function getUser()
        {
            return $this->_user;
        }

        /**
         * Add function info here
         */
        function getGroup()
        {
            return $this->_group;
        }

        /**
         * Add function info here
         */
        function getTimeStamp()
        {
            return $this->_timeStamp;
        }

        /**
         * Add function info here
         */
        function isDir()
        {
            return $this->_isDir;
        }

        /**
         * Add function info here
         */
        function setName($name)
        {
            $this->_name = $name;
        }

        /**
         * Add function info here
         */
        function setSize($size)
        {
            $this->_size = $size;
        }

        /**
         * Add function info here
         */
        function setPermissions($perms)
        {
            $this->_permissions = $perms;
        }

        /**
         * Add function info here
         */
        function setUser($user)
        {
            $this->_user = $user;
        }

        /**
         * Add function info here
         */
        function setGroup($group)
        {
            $this->_group = $group;
        }

        /**
         * Add function info here
         */
        function setTimeStamp($timeStamp)
        {
            $this->_timeStamp = $timeStamp;
        }

        /**
         * Add function info here
         */
        function setIsDir($isDir)
        {
            $this->_isDir = $isDir;
        }
     }
?>

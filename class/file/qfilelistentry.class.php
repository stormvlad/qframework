<?php

     include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * @brief Representa una entrada en un listado de archivos
     * 
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 13:59
     * @version 1.0
     * @ingroup file
     * @see qFileList
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
        function getNormalizedSize($decimals = 0)
        {
            $size  = $this->_size;
            $sizes = array("B", "KB", "MB", "GB", "TB", "PB", "EB");
            $ext   = $sizes[0];
            $count = count($sizes);

            for ($i = 1; ($i < $count) && ($size >= 1024); $i++)
            {
                $size = $size / 1024;
                $ext  = $sizes[$i];
            }

            return round($size, $decimals). " " . $ext;
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
        function getExtension()
        {
            $fileName = $this->getName();

            if (($pos = strrpos($fileName, ".")) !== false)
            {
                return substr($fileName, $pos + 1, strlen($fileName) - $pos - 1);
            }
            else
            {
                return false;
            }
        }

        /**
         * Add function info here
         */
        function isFile()
        {
            return !$this->_isDir;
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

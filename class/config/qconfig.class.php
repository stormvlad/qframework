<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qconfigfilestorage.class.php");

    define("DEFAULT_CONFIG_FILE_STORAGE", "config/config.properties.php");

    /**
     * Extends the Properties class so that our own configuration file is automatically loaded.
     * The configuration file is under config/config.properties.php
     *
     * It is recommented to use this function as a singleton rather than as an object.
     * @see Config
     * @see getConfig
     */
    class qConfig extends qObject
    {
        var $_storage;
        var $_props;

        /**
        *    Add function info here
        */
        function qConfig(&$storage)
        {
            $this->qObject();

            $this->_storage = &$storage;
            $this->_props   = new qProperties();
            $this->load();
        }

        /**
        *    Add function info here
        */
        function &getConfig()
        {
            static $configInstance;

            if (!isset($configInstance))
            {
                $configInstance = new qConfig(new qConfigFileStorage(DEFAULT_CONFIG_FILE_STORAGE));
            }

            return $configInstance;
        }

        /**
        *    Add function info here
        */
        function load()
        {
            return $this->_storage->load($this);
        }

        /**
        *    Add function info here
        */
        function saveValue($name, $value)
        {
            return $this->_storage->saveValue($this, $name, $value);
        }

        /**
        *    Add function info here
        */
        function save()
        {
            return $this->_storage->save($this);
        }

        /**
        *    Add function info here
        */
        function getValue($key, $defaultValue = null)
        {
            $value = $this->_props->getValue($key);

            if ($defaulValue !== null && empty($value))
            {
                $value = $defaultValue;
            }

            return $value;
        }

        /**
        *    Add function info here
        */
        function setValues($values)
        {
            return $this->_props->setValues($values);
        }

        /**
        *    Add function info here
        */
        function setValue($key, $value)
        {
            return $this->_props->setValue($key, $value);
        }

        /**
        *    Add function info here
        */
        function getKeys()
        {
            return $this->_props->getKeys();
        }

        /**
        *    Add function info here
        */
        function getValues()
        {
            return $this->_props->getValues();
        }

        /**
        *    Add function info here
        */
        function getAsArray()
        {
            return $this->_props->getAsArray();
        }

        /**
        *    Add function info here
        */
        function keyExists($key)
        {
            return $this->_props->keyExists($key);
        }
    }
?>

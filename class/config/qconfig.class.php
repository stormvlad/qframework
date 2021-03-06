<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qconfigfilestorage.class.php");

    define("DEFAULT_CONFIG_FILE_STORAGE", "config/config.properties.php");

    /**
     * @defgroup config Configuraci�n
     */
     
    /**
     * @brief Almacena la configuraci�n de la aplicaci�n
     *
     * Extiende la clase Properties d�nde se cargan todos los datos de configuraci�n.
     * Los datos pueden almacenarse en un fichero, base de datos, ...
     * seg�n el objeto de almacenamiento definido.
     *
     * @author  qDevel - info@qdevel.com
     * @date    08/03/2005 00:34
     * @version 1.0
     * @ingroup config
     */
    class qConfig extends qObject
    {
        var $_storage;
        var $_props;

        /**
         * Constructor
         *
         * @param storage Objeto del tipo qConfigStorage que define la forma de almacenamiento de la configuraci�n
         */
        function qConfig(&$storage)
        {
            $this->qObject();

            $this->_storage = &$storage;
            $this->_props   = new qProperties();
            $this->load();
        }

        /**
         * Devuelve la �nica instancia de qConfig
         *
         * @note Basado en el patr�n Singleton. El objectivo de este m�todo es asegurar que exista s�lo una instancia de esta clase y proveer de un punto global de accesso a ella.
         * @return qConfig
         */
        function &getInstance()
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
        function saveValue($name, $value = null)
        {
            if (!empty($value))
            {
                $this->setValue($name, $value);
            }

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

            if ($defaultValue !== null && empty($value))
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

<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qconfigstorage.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/database/qdb.class.php");

    /**
     * Storage backend that stores/retrieves the data from the plog_config
     * table.<br/>
     * The structore of the table is as follows:<ul>
     * <li>id: setting identifier</li>
     * <li>config_key: Name of the setting. Can't be empty</li>
     * <li>config_value: Value assigned to the key</li>
     * <li>value_type: This field can take several values and gives the class
     * a hint regardign the type of the value:<ul>
     * <li>1: integer. The config_value field represents is value.</li>
     * <li>2: boolean. It is saved as 1 == true and 0 == false.</li>
     * <li>3: string. It is saved as is.</li>
     * <li>4: object. The object is saved in a seralized way.</li>
     * <li>5: array. The arrays are also saved serialized. This is transparently
     * done inside the save() and saveValue() methods, and therefore the user
     * does not have to worry about doing it.</li>
     * <li>6: float. It is saved as is.</li>
     * </ul>
     * Type detection is provided via the built-in mechanisms that PHP offers.
     * </ul>
     */
    class qConfigDbStorage extends qConfigStorage
    {
        var $_db;
        var $_tableName;

        /**
         * Connects to the database using the parameters in the config file.
         *
         */
        function qConfigDbStorage(&$db, $tableName)
        {
            $this->qConfigStorage();

            $this->_db        = &$db;
            $this->_tableName = $tableName;
        }

        /**
         * Internal function that loads all the data from the table and puts in into
         * our array. It should be apparently faster that making an SQL query every time
         * we need to get a value.
         *
         * @private
         */
        function _keyExists($key)
        {
            $query  = "SELECT * FROM " . $this->_tableName . " WHERE config_key='" . $key . "'";
            $result = $this->_db->Execute($query);

            if (!$result)
            {
                return false;
            }

            if (!$result->FetchRow())
            {
                return false;
            }

            return true;
        }

        /**
         * Internal function that loads all the data from the table and puts in into
         * our array. It should be apparently faster that making an SQL query every time
         * we need to get a value.
         *
         * @private
         */
        function load(&$config)
        {
            $query  = "SELECT * FROM " . $this->_tableName;
            $result = $this->_db->Execute($query);

            if (!$result)
            {
                throw(new qException("ConfigDbStorage::load: There was an error loading the configuration data from the database."));
                die();
            }

            while ($row = $result->FetchRow())
            {
                $key      = $row["config_key"];
                $value    = $row["config_value"];
                $dataType = $row["value_type"];

                if ($dataType == TYPE_OBJECT || $dataType == TYPE_ARRAY)
                {
                    $config->setValue($key, unserialize(stripslashes($value)));

                    if ($dataType == TYPE_ARRAY && $this->_data[$key] == "")
                    {
                        $config->setValue($key, array());
                    }
                }
                else
                {
                    $config->setValue($key, $value);
                }
            }

            return true;
        }

        /**
        *    Add function info here
        */
        function _updateValue(&$config, $key, $value)
        {
            $type = $this->getType($value);

            switch ($type)
            {
                 case TYPE_INTEGER:
                 case TYPE_BOOLEAN:
                 case TYPE_FLOAT:
                    $query = "UPDATE " . $this->_tableName . " SET config_value = '$value', value_type = $type WHERE config_key = '$key'";
                    break;

                 case TYPE_STRING:
                     $query = "UPDATE " . $this->_tableName . " SET config_value ='" . qDb::qstr($value) . "', value_type = $type WHERE config_key = '$key'";
                    break;

                 case TYPE_ARRAY:
                 case TYPE_OBJECT:
                     $serValue = addslashes(serialize($value));
                     $query    = "UPDATE " . $this->_tableName . " SET config_value = '$serValue', value_type = $type WHERE config_key = '$key'";
                    break;

                 default:
                     throw(new qException("ConfigDbStorage::_updateValue: getType produced an unexpected value of " . $type . " when checking value '" . $value . "'"));
                    die();
             }

             return $this->_db->Execute($query);
        }

        /**
        *    Add function info here
        */
        function _insertValue(&$config, $key, $value)
        {
            $type = $this->getType($value);

            switch ($type)
            {
                case TYPE_INTEGER:
                case TYPE_BOOLEAN:
                case TYPE_FLOAT:
                    $query = "INSERT INTO " . $this->_tableName . " (config_key, config_value, value_type) VALUES ('$key', '$value', $type)";
                    break;

                case TYPE_STRING:
                     $query = "INSERT INTO " . $this->_tableName . " (config_key, config_value, value_type) VALUES ('$key', '" . Db::qstr($value) . "', $type )";
                     break;

                case TYPE_ARRAY:
                case TYPE_OBJECT:
                    $serValue = addslashes(serialize($value));
                    $query    = "INSERT INTO " . $this->_tableName . " (config_key, config_value, value_type) VALUES ('$key', '$serValue', $type)";
                    break;

                default:
                    throw(new qException("ConfigDbStorage::_insertValue: getType produced an unexpected value of $type"));
                    die();
             }

             return $this->_db->Execute($query);
        }

        /**
         * Puts just one setting back to the database.
         *
         * It is done so that we first check if the key exists. If it does, we then
         * send an update query and update it. Otherwise, we add it.
         *
         * @param key The name of the key
         * @param The value.
         * @return True if successful or false otherwise
         */
        function saveValue(&$config, $key, $value)
        {
            if ($this->_keyExists($key))
            {
                $result = $this->_updateValue($config, $key, $value);
            }
            else
            {
                 $result = $this->_insertValue($config, $key, $value);
            }

            return $result;
        }

        /**
         * Puts all the settings back to the database.
         *
         * It is done so that we first check if the key exists. If it does, we then
         * send an update query and update it. Otherwise, we add it.
         *
         * @param key The name of the key
         * @param The value.
         * @return True if successful or false otherwise
         */
        function save(&$config)
        {
            $result = true;
            $data   = $config->getAsArray();

            foreach($data as $key => $value)
            {
                $result &= $this->saveValue($config, $key, $value);
            }

            return $result;
        }
    }
?>

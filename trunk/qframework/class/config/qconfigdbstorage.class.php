<?php

    include_once("framework/class/object/qobject.class.php" );
    include_once("framework/class/config/qconfigabstractstorage.class.php");
    include_once("framework/class/database/qdb.class.php");

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
    class qConfigDbStorage extends qConfigAbstractStorage {

        var $_db;
        var $_data;
        var $_tableName;

        /**
         * Connects to the database using the parameters in the config file.
         *
         */
        function qConfigDbStorage(&$db, $tableName)
        {
            $this->qConfigAbstractStorage();

            $this->_db        = &$db;
            $this->_tableName = $tableName;
            $this->reload();
        }

        /**
         * Internal function that loads all the data from the table and puts in into
         * our array. It should be apparently faster that making an SQL query every time
         * we need to get a value.
         *
         * @private
         */
        function reload()
        {
            $this->_data = Array();
            $query       = "SELECT * FROM " . $this->_tableName;
            $result      = $this->_db->Execute($query);

            if (!$result)
            {
                throw(new qException("ConfigDbStorage::_loadData: There was an error loading the configuration data from the database. And this is bad ..."));
                die();
            }

            while ($row = $result->FetchRow())
            {
                $key      = $row["config_key"];
                $value    = $row["config_value"];
                $dataType = $row["value_type"];

                if ($dataType == TYPE_OBJECT || $dataType == TYPE_ARRAY)
                {
                    $this->_data[$key] = unserialize( stripslashes($value));

                    if ($dataType == TYPE_ARRAY && $this->_data[$key] == "")
                    {
                        $this->_data[$key] = Array();
                    }
                }
                else
                {
                    $this->_data[$key] = $value;
                }
            }

            return true;
        }

        function getValue($key, $defaultValue = null)
        {
            $value = $this->_data[$key];

            if (empty($value))
            {
                if ($defaulValue !== null)
                {
                    $value = $defaultValue;
                }
            }

            return $value;
        }

        function setValue($key, $value)
        {
            $this->_data[$key] = $value;

            return true;
        }

        function getAsArray()
        {
            return $this->_data;
        }

        function getConfigFileName()
        {
            return "database";
        }

        function getKeys()
        {
            return array_keys($this->_data);
        }

        function getValues()
        {
            return array_values($this->_data);
        }

        function keyExists($key)
        {
            return isset($this->_data[$key]);
        }

        function _updateValue($key, $value)
        {
            $type = $this->_getType($value);

            switch ($type)
            {
                 case TYPE_INTEGER:
                 case TYPE_BOOLEAN:
                 case TYPE_FLOAT:
                    $query = "UPDATE " . $this->_tableName . " SET config_value = '$value', value_type = $type WHERE config_key = '$key'";
                    break;

                 case TYPE_STRING:
                     $query = "UPDATE " . $this->_tableName . " SET config_value ='" . Db::qstr($value) . "', value_type = $type WHERE config_key = '$key'";
                    break;

                 case TYPE_ARRAY:
                 case TYPE_OBJECT:
                     $serValue = addslashes(serialize($value));
                     $query    = "UPDATE " . $this->_tableName . " SET config_value = '$serValue', value_type = $type WHERE config_key = '$key'";
                    break;

                 default:
                     throw( new qException( "ConfigDbStorage::_updateValue: _getType produced an unexpected value of $type when checking value \"$value\""));
                    die();
             }

             //$this->_db->debug=true;
             return $this->_db->Execute($query);
        }

        function _insertValue($key, $value)
        {
            $type = $this->_getType( $value );

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
                    throw(new qException("ConfigDbStorage::_insertValue: _getType produced an unexpected value of $type"));
                    die();
             }

             return $this->_db->Execute( $query );
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
        function save()
        {
            foreach($this->_data as $key => $value)
            {
                $this->saveValue($key, $value);
            }

            // saveValue is already reloading the data for us everytime!
            return true;
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
        function saveValue( $key, $value )
        {
            if ($this->keyExists($key))
            {
                $result = $this->_updateValue($key, $value);
            }
            else
            {
                 $result = $this->_insertValue($key, $value);
            }

            $this->reload();

            return $result;
        }
    }
?>

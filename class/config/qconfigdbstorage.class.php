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
         * Reloads the contents from the configuration file.
         *
         * @return Returns always true.
         */
        function load(&$cfg)
        {
            $settings = $this->getAll();

            if (!$settings)
            {
                return false;
            }        

            foreach ($settings as $setting)
            {
                switch($setting["value_type"])
                {
                    case 1: // integer
                        $value = intval($setting["config_value"]);
                        break;
                        
                    case 2: // boolean
                        $value = ($setting["config_value"] === 1);
                        break;

                    case 4: // objects
                        $value = unserialize($setting["config_value"]);
                        break;

                    case 5: // array
                        break;

                    default: // strings (3) and others
                        $value = $setting["config_value"];
                }
                
                $cfg->setValue($setting["config_key"], $value);
            }                        

            return true;
        }        
        
        /**
        *    Add function info here
        */
        function getAll()
        {
            $rs = $this->_db->Execute("SELECT * FROM `" . $this->_tableName . "`");

            if (!$rs)
            {
                return false;
            }        

            $settings = array();
            
            while ($setting = $rs->FetchRow())
            {
                $settings[] = $setting;
            }
            
            return $settings;
        }

        /**
        *    Add function info here
        */
        function save(&$config)
        {
            $settings = $this->getAll();
            $result   = true;

            if (!$settings)
            {
                return false;
            }        

            foreach ($settings as $setting)
            {
                if ($config->keyExists($setting["config_key"]))
                {
                    $value = $config->getValue($setting["config_key"]);

                    if($setting["value_type"] == 4)
                    {
                        $value = serialize($value);
                    }                    

                    $result &= $this->_db->Execute(  " UPDATE `" . $this->_tableName . "` "
                                                   . " SET config_value='" . $value . "'"
                                                   . " WHERE config_key='" . $setting["config_key"] . "'");
                }
            }                        

            return $result;
        }        
    }
?>

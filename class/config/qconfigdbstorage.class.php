<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qconfigstorage.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/dao/qdb.class.php");

    /**
     * @brief Servicio de almacenaje (backend) en base de datos para datos de configuración 
     * 
     * La estructura de la tabla és como la siguiente:<ul>
     * <li>id: identificador autonumérico</li>
     * <li>config_key: clave o nombre de la configuración. no puede ser vacio</li>
     * <li>config_value: valor asignado a la clave</li>
     * <li>value_type: este campo puede tomar los siguientes valores:
     * <ul>
     *  <li>1: integer. Nombres enteros.</li>
     *  <li>2: boolean. Booleano, se guarda verdadero=1 i falso=0</li>
     *  <li>3: string. Cadena de carácteres, se guarda tál cual.</li>
     *  <li>4: object. Objetos, se guarda serializando (convertir en cadena).</li>
     *  <li>5: array. Vectores i matrices. Se guardan también serializados</li>
     *  <li>6: float. Nombres com coma flotante</li>
     * </ul>
     * <p>La serialización de los datos se hace de forma transparente al usuario.
     * La detección del tipo de datos se hace con el sistema integrado en PHP</p>
     * </ul>
     *
     * @author  qDevel - info@qdevel.com
     * @date    12/03/2005 20:28
     * @version 1.0
     * @ingroup config
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

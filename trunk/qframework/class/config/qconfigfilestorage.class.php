<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfile.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qconfigstorage.class.php");

    /**
     * @brief Servicio de almacenaje (backend) en fichero de texto para datos de configuración
     *
     * El fichero de texto con la configuración se carga/guarda en config/config.properties.php
     *
     * @author  qDevel - info@qdevel.com
     * @date    12/03/2005 23:10
     * @version 1.0
     * @ingroup config
     */
    class qConfigFileStorage extends qConfigStorage
    {
        var $_configFile;

        /**
         * Abre el fichero de configuración. Por defecto es config/config.properties.php
         * sino se especifica ningún parámetro.
         *
         * @param configFile Nombre del archivo de configuración
         */
        function qConfigFileStorage($configFile)
        {
            $this->qConfigStorage();
            $this->_configFile = $configFile;
        }

        /**
         * Recarga el contenido del fichero de configuración
         *
         * @param $cfg qConfig Instancia de qConfig, configuración usada
         * @return Devuelve siempre TRUE
         */
        function load(&$cfg)
        {
            include($this->_configFile);
            $cfg->setValues($config);

            return true;
        }

        /**
         * Devuelve el nombre del fichero de configuración que se esta usando
         *
         * @return string Nombre del fichero de configuración
         */
        function getConfigFileName()
        {
            return $this->_configFile;
        }

        /**
         * Guarda un parámetro en el fichero de configuración. Si el parámetro ya existe, el actual
         * valor se sobreesribre. En otro caso, se añade al final del fichero.
         *
         * @param config qConfig Instancia de qConfig, configuración usada
         * @param name Name of the setting.
         * @param value Value of the setting.
         * @return True if success or false otherwise.
         * @note Este método no esta optimizado porque cada vez que llamamos a saveValue,
         * se escribira toto el fichero al disco. Podria mejorarse la implementación.
         */
        function saveValue(&$config, $name, $value)
        {
            $f = new qFile($this->_configFile);

            if (!$f->open("r+"))
            {
                return false;
            }

            $contents    = $f->readFile();
            $i           = 0;
            $result      = Array();
            $valueString = $this->getDataString($value);

            if ($this->getType($value) == TYPE_STRING)
            {
                $regexp      = "/([ \t]*)\\\$config\[\"" . $name. "\"\]( *)=( *)\"(.+)\";( *)/";
                $replaceWith = "\\1\$config[\"" . $name . "\"]\\2=\${3}" . $valueString . ";\\5";
            }
            else
            {
                $regexp      = "/([ \t]*)\\\$config\[\"" . $name . "\"\]( *)=( *)(.+);( *)/";
                $replaceWith = "\\1\$config[\"" . $name . "\"]\\2=\${3}" . $valueString . ";\\5";
            }

            while ($i < count($contents))
            {
                $line    = $contents[$i];
                $newline = preg_replace($regexp, $replaceWith, $line);
                $i++;

                if ($newline != "?>")
                {
                    $newline .= "\n";
                    array_push($result, $newline);
                }
                else
                {
                    array_push($result, "?>");
                    break;
                }
            }

            $f->writeLines($result);
            $config->setValue($name, $value);

            return true;
        }

        /**
         * Guarda todos los parámetros al fichero de configuración
         *
         * @param config qConfig Instancia de qConfig, configuración usada
         */
        function save(&$config)
        {
            $result = true;
            $data   = $config->getAsArray();

            foreach($data as $key => $value)
            {
                $result &= $this->saveValue($key, $value);
            }

            return $result;
        }
    }
?>
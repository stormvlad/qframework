<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfile.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qconfigstorage.class.php");

    /**
     * @brief Servicio de almacenaje (backend) en fichero de texto para datos de configuraci�n
     *
     * El fichero de texto con la configuraci�n se carga/guarda en config/config.properties.php
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
         * Abre el fichero de configuraci�n. Por defecto es config/config.properties.php
         * sino se especifica ning�n par�metro.
         *
         * @param configFile Nombre del archivo de configuraci�n
         */
        function qConfigFileStorage($configFile)
        {
            $this->qConfigStorage();
            $this->_configFile = $configFile;
        }

        /**
         * Recarga el contenido del fichero de configuraci�n
         *
         * @param $cfg qConfig Instancia de qConfig, configuraci�n usada
         * @return Devuelve siempre TRUE
         */
        function load(&$cfg)
        {
            include($this->_configFile);
            $cfg->setValues($config);

            return true;
        }

        /**
         * Devuelve el nombre del fichero de configuraci�n que se esta usando
         *
         * @return string Nombre del fichero de configuraci�n
         */
        function getConfigFileName()
        {
            return $this->_configFile;
        }

        /**
         * Guarda un par�metro en el fichero de configuraci�n. Si el par�metro ya existe, el actual
         * valor se sobreesribre. En otro caso, se a�ade al final del fichero.
         *
         * @param config qConfig Instancia de qConfig, configuraci�n usada
         * @param name Name of the setting.
         * @param value Value of the setting.
         * @return True if success or false otherwise.
         * @note Este m�todo no esta optimizado porque cada vez que llamamos a saveValue,
         * se escribira toto el fichero al disco. Podria mejorarse la implementaci�n.
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
         * Guarda todos los par�metros al fichero de configuraci�n
         *
         * @param config qConfig Instancia de qConfig, configuraci�n usada
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
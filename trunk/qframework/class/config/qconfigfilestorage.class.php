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
         * Opens the configuration file. By default it is config/config.properties.php
         * if no parameter is specified. If there is a parameter specified, that
         * is the file the constructor will try to open.
         * If no file name is specified, it defaults to config/config.properties.php.
         *
         * @param configFile The name of the file we would like to use.
         */
        function qConfigFileStorage($configFile)
        {
            $this->qConfigStorage();
            $this->_configFile = $configFile;
        }

        /**
         * Reloads the contents from the configuration file.
         *
         * @return Returns always true.
         */
        function load(&$cfg)
        {
            include($this->_configFile);
            $cfg->setValues($config);
            return true;
        }

        /**
         * Returns the name of the configuration file being used.
         *
         * @return The name of the configuration file being used.
         */
        function getConfigFileName()
        {
            return $this->_configFile;
        }

        /**
         * Saves a setting to the configuration file. If the setting already exists, the current
         * value is overwritten. Otherwise, it will be appended in the end of the file.
         * <b>NOTE:</b> This method is highly unoptimized because every time that we call saveValue,
         * we are writing the whole file to disk... Bad ;) But it works, so we'll leave it as it
         * is for the time being...
         *
         * @param config
         * @param name Name of the setting.
         * @param value Value of the setting.
         * @return True if success or false otherwise.
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
        *    Add function info here
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
<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfile.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qconfigabstractstorage.class.php");

    /**
     * Extends the Properties class so that our own configuration file is automatically loaded.
     * The configuration file is under config/config.properties.php
     */
    class qConfigFileStorage extends qConfigAbstractStorage
    {
        var $_configFile;
        var $_props;

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
            $this->qConfigAbstractStorage();

            $this->_configFile = $configFile;
            $this->reload();
        }

        /**
         * Reloads the contents from the configuration file.
         *
         * @return Returns always true.
         */
        function reload()
        {
            include($this->_configFile);

            $this->_props = new qProperties( $config );

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
         * Private function that given a piece of PHP data, will return an string representing
         * it, literally. Examples:
         *
         * data is a boolean type. Result --> the string 'true'
         * data is string type. Result --> string "value_of_the_string"
         * data is an array. Result --> string containing "Array( "..", "...", "..") "
         *
         * Objects are saved serialized and since there is no way to detect if it's an object
         * or not, it will be up to the user of the class to de-serialize it.
         *
         * <b>:TODO:</b> This function does not handle very well sparse arrays, but it does
         * handles arrays within arrays.
         *
         * @private
         * @param data The data we'd like to get the string representation
         * @return An string representing the data, so that eval'ing it would yield
         * the the same result as the $data parameter.
         */
        function _getDataString($data)
        {
            if ($this->_getType( $data ) == TYPE_INTEGER)
            {
                $dataString = $data;
            }
            elseif ($this->_getType( $data ) == TYPE_BOOLEAN)
            {
                if ($data)
                {
                    $dataString = "true";
                }
                else
                {
                    $dataString = "false";
                }
            }
            elseif ($this->_getType( $data ) == TYPE_STRING)
            {
                $dataString = "\"$data\"";
            }
            elseif ($this->_getType($data) == TYPE_ARRAY)
            {
                $dataString = "Array (";

                foreach ($data as $key => $item)
                {
                    if ($key != "")
                    {
                        if (!is_numeric($key))
                        {
                            $dataString .= "\"$key\" => ";
                        }
                        /*else
                        {
                            $dataString .= "$key => ";
                        }*/
                    }

                    $dataString .= $this->_getDataString($item ) . ",";
                }

                if ($dataString[strlen($dataString)-1] == ",")
                {
                    $dataString[strlen($dataString)-1] = ")";
                }
                else
                {
                    $dataString .= ")";
                }

                print("dataString = ".$dataString."<br/>");
            }
            elseif ($this->_getType( $data ) == TYPE_OBJECT)
            {
                $dataString = serialize($data);
            }

            return $dataString;
        }

        /**
         * Saves a setting to the configuration file. If the setting already exists, the current
         * value is overwritten. Otherwise, it will be appended in the end of the file.
         * <b>NOTE:</b> This method is highly unoptimized because every time that we call saveValue,
         * we are writing the whole file to disk... Bad ;) But it works, so we'll leave it as it
         * is for the time being...
         *
         * @param name Name of the setting.
         * @param value Value of the setting.
         * @return True if success or false otherwise.
         */
        function saveValue($name, $value)
        {
            $f = new qFile( $this->_configFile );

            if (!$f->open("r+"))
            {
                return false;
            }

            $contents    = $f->readFile();
            $i           = 0;
            $result      = Array();
            $valueString = $this->_getDataString($value);

            if ($this->_getType($value) == TYPE_STRING)
            {
                $regexp      = "/( *)\\\$config\[\"$name\"\]( *)=( *)\"(.+)\";( *)/";
                //$replaceWith = "\$config[\"$name\"] = $valueString;";
                $replaceWith = "\\1\$config[\"$name\"]\\2=\\3$valueString;\\5";
            }
            else
            {
                $regexp      = "/( *)\\\$config\[\"$name\"\]( *)=( *)(.+);( *)/";
                $replaceWith = "\\1\$config[\"$name\"]\\2=\\3$valueString;\\5";
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
            $this->setValue($name, $value);

            return true;
        }

        function keyExists($key)
        {
            return $this->_props->keyExists($key);
        }

        function getValue( $key, $defaultValue = null )
        {
            $value = $this->_props->getValue($key);

            if ($value == "" || $value == null)
            {
                if ($defaulValue != null)
                {
                    $value = $defaultValue;
                }
            }

            return $value;
        }

        function setValue($key, $value )
        {
            return $this->_props->setValue( $key, $value );
        }

        function getKeys()
        {
            return $this->_props->getKeys();
        }

        function getValues()
        {
            return $this->_props->getValues();
        }

        function getAsArray()
        {
            return $this->_props->getAsArray();
        }

        function save()
        {
            foreach ($this->_props->getAsArray() as $key => $value)
            {
                $this->saveValue($key, $value);
            }

            return true;
        }
    }
?>
<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    define(TYPE_INTEGER, 1);
    define(TYPE_BOOLEAN, 2);
    define(TYPE_STRING,  3);
    define(TYPE_OBJECT,  4);
    define(TYPE_ARRAY,   5);
    define(TYPE_FLOAT,   6);

    /**
     * Interface class that defines the methods that should be implemented
     * by child classes wishing to implement a configuratino settings storage backend.
     */
    class qConfigStorage extends qObject
    {
        function qConfigStorage()
        {
            $this->qObject();
        }

        /**
         * Returns a constant determining the type of the value passed as parameter. The constants
         * are:<ul>
         * <li>TYPE_INTEGER = 1</li>
         * <li>TYPE_BOOLEAN = 2</li>
         * <li>TYPE_STRING = 3</li>
         * <li>TYPE_OBJECT = 4</li>
         * <li>TYPE_ARRAY = 5</li>
         * <li>TYPE_FLOAT = 6</li>
         * </ul>
         *
         * @param value The value from which we'd like to know its type
         * @return Returns one of the above.
         */
        function getType( $value )
        {
            if (is_integer($value))
            {
                $type = TYPE_INTEGER;
            }
            elseif (is_float($value))
            {
                $type = TYPE_FLOAT;
            }
            elseif (is_bool($value))
            {
                $type = TYPE_BOOLEAN;
            }
            elseif (is_string($value))
            {
                $type = TYPE_STRING;
            }
            elseif (is_object($value))
            {
                $type = TYPE_OBJECT;
            }
            elseif (is_array($value))
            {
                $type = TYPE_ARRAY;
            }
            else
            {
                $type = TYPE_STRING;
            }

            return $type;
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
        function getDataString($data)
        {
            if ($this->getType( $data ) == TYPE_INTEGER)
            {
                $dataString = $data;
            }
            elseif ($this->getType( $data ) == TYPE_BOOLEAN)
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
            elseif ($this->getType( $data ) == TYPE_STRING)
            {
                $dataString = "\"" . $data . "\"";
            }
            elseif ($this->getType($data) == TYPE_ARRAY)
            {
                $dataString = "Array (";

                foreach ($data as $key => $item)
                {
                    if ($key != "")
                    {
                        if (!is_numeric($key))
                        {
                            $dataString .= "\"" . $key . "\" => ";
                        }
                        /*else
                        {
                            $dataString .= "$key => ";
                        }*/
                    }

                    $dataString .= $this->_getDataString($item) . ",";
                }

                if ($dataString[strlen($dataString)-1] == ",")
                {
                    $dataString[strlen($dataString)-1] = ")";
                }
                else
                {
                    $dataString .= ")";
                }
            }
            elseif ($this->getType( $data ) == TYPE_OBJECT)
            {
                $dataString = serialize($data);
            }

            return $dataString;
        }

        /**
         * Reloads the contents from the configuration file.
         *
         * @return Returns always true.
         */
        function load(&$config)
        {
            throw(new qException("qConfigStorage::load: This method must be implemented by child classes."));
            die();
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
        function saveValue(&$config, $name, $value)
        {
            throw(new qException("qConfigStorage::saveValue: This method must be implemented by child classes."));
            die();
        }

        function save(&$config)
        {
            throw(new qException("qConfigStorage::save: This method must be implemented by child classes."));
            die();
        }
    }
?>

<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
     * Class inspired by the java class Properties
     */
    class qProperties extends qObject
    {
        var $_props;

        /**
         * Constructor.
         *
         * @param values If $values == null, then the object will be initialized empty.
         * If it contains a valid PHP array, all the properties will be initialized at once.
         */
        function qProperties($values = null)
        {
            $this->qObject();

            if ($values == null)
            {
                $this->_props = array();
            }
            else
            {
                $this->_props = $values;
            }
        }

        /**
         * Sets a value in our hash table.
         *
         * @param key Name of the value in the hash table
         * @param value Value that we want to assign to the key '$key'
         */
        function setValues($values)
        {
            $this->_props = $values;
        }

        /**
         * Sets a value in our hash table.
         *
         * @param key Name of the value in the hash table
         * @param value Value that we want to assign to the key '$key'
         */
        function setValue($key, $value)
        {
            $this->_props[$key] = $value;
        }

        /**
         *
         * Add function info here
         *
         */
        function keyExists($key)
        {
            return array_key_exists($key, $this->_props);
        }

        /**
         * Returns the value associated to a key
         *
         * @param key Key whose value we want to fetch
         * @return Value associated to that key
         */
        function getValue($key)
        {
            if (isset($this->_props[$key]))
            {
                return $this->_props[$key];
            }
            else
            {
                return false;
            }
        }

        /**
         * Method overwritten from the Object class
         * @return Returns a nicer representation of our contents
         */
        function toString()
        {
            print_r($this->_props);
        }

        /**
         * Returns the internal arrary used to store the properties as a PHP array
         * @return Internal array as a PHP array
         */
        function getAsArray()
        {
            return $this->_props;
        }

        /**
         * Returns an array containing all the keys used
         *
         * @return Array containing all the keys
         */
        function getKeys()
        {
            return array_keys($this->_props);
        }

        /**
         * Returns an array containing the values
         *
         * @return Array containing the values
         */
        function getValues()
        {
            return array_values($this->_props);
        }

        /**
         *
         * Add function info here
         *
         */
        function count()
        {
            return count($this->_props);
        }
    }
?>

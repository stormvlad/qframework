<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    define("PROPERTIES_SLASHES_NONE", 0);
    define("PROPERTIES_SLASHES_ADD", 1);
    define("PROPERTIES_SLASHES_STRIP", 2);

    /**
     * @brief Mapa de valores definidos por una clave única
     *
     * Clase inspirada en la clase de Java Properties
     *
     * @author  qDevel - info@qdevel.com
     * @date    12/03/2005 23:36
     * @version 1.0
     * @ingroup core
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
         * @param values Array asociativo con los nombres y valores
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
        function removeValue($key)
        {
            unset($this->_props[$key]);
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
         * @param slashes int Método para tratar con las barras
         * @return Value associated o that key
         */
        function getValue($key, $slashes = PROPERTIES_SLASHES_NONE)
        {
            if (array_key_exists($key, $this->_props))
            {
                switch ($slashes)
                {
                    case PROPERTIES_SLASHES_ADD:
                        return addSlashes($this->_props[$key]);

                    case PROPERTIES_SLASHES_STRIP:
                        return stripSlashes($this->_props[$key]);

                    default:
                        return $this->_props[$key];
                }
            }
            else
            {
                return false;
            }
        }

        /**
         * Devuelve un valor asociado a la clave especificada
         *
         * @param key Key whose value we want to fetch
         * @return Referencia al valor asociado
         */
        function &getValueRef($key)
        {
            if (array_key_exists($key, $this->_props))
            {
                return $this->_props[$key];
            }
            else
            {
                return false;
            }
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

        /**
         *
         * Add function info here
         *
         */
        function reset()
        {
            $this->_props = array();
        }
    }
?>

<?php

    include_once("framework/class/object/object.class.php" );

    define( TYPE_INTEGER, 1 );
    define( TYPE_BOOLEAN, 2 );
    define( TYPE_STRING,  3 );
    define( TYPE_OBJECT,  4 );
    define( TYPE_ARRAY,   5 );
    define( TYPE_FLOAT,   6 );

    /**
     * Interface class that defines the methods that should be implemented
     * by child classes wishing to implement a configuratino settings storage backend.
     */
    class ConfigAbstractStorage extends Object {

        function ConfigAbstractStorage()
        {
            $this->Object();
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
        function _getType( $value )
        {
            if( is_integer( $value ))
                $type = TYPE_INTEGER;
            elseif( is_float( $value ))
                $type = TYPE_FLOAT;
            elseif( is_bool( $value ))
                $type = TYPE_BOOLEAN;
            elseif( is_string( $value ))
                $type = TYPE_STRING;
            elseif( is_object( $value ))
                $type = TYPE_OBJECT;
            elseif( is_array( $value ))
                $type = TYPE_ARRAY;
            else
                $type = TYPE_STRING;

            //print("type = ".$type."<br/>" );

            return $type;
        }

        function keyExists($key)
        {
            throw( new Exception( "ConfigAbstractStorage::keyExists: This method must be implemented by child classes." ));
            die();
        }

        function getValue( $key, $defaultValue = null )
        {
            throw( new Exception( "ConfigAbstractStorage::getValue: This method must be implemented by child classes." ));
            die();
        }

        function setValue( $key, $value )
        {
            throw( new Exception( "ConfigAbstractStorage::setValue: This method must be implemented by child classes." ));
            die();
        }

        function getAsArray()
        {
            throw( new Exception( "ConfigAbstractStorage::getAsArray: This method must be implemented by child classes." ));
            die();
        }

        function reload()
        {
            throw( new Exception( "ConfigAbstractStorage::reload: This method must be implemented by child classes." ));
            die();
        }

        function getConfigFileName()
        {
            throw( new Exception( "ConfigAbstractStorage::getConfigFileName: This method must be implemented by child classes." ));
            die();
        }

        function getKeys()
        {
            throw( new Exception( "ConfigAbstractStorage::getKeys: This method must be implemented by child classes." ));
            die();
        }

        function getValues()
        {
            throw( new Exception( "ConfigAbstractStorage::getValues: This method must be implemented by child classes." ));
            die();
        }

        function saveValue( $key, $value )
        {
            throw( new Exception( "ConfigAbstractStorage::saveValue: This method must be implemented by child classes." ));
            die();
        }

        function save()
        {
            throw( new Exception( "ConfigAbstractStorage::saveValue: This method must be implemented by child classes." ));
            die();
        }
    }
?>

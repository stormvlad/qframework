<?php

    include_once("qframework/class/object/qobject.class.php" );

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
    class qConfigAbstractStorage extends qObject {

        function qConfigAbstractStorage()
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
            throw( new qException( "qConfigAbstractStorage::keyExists: This method must be implemented by child classes." ));
            die();
        }

        function getValue( $key, $defaultValue = null )
        {
            throw( new qException( "qConfigAbstractStorage::getValue: This method must be implemented by child classes." ));
            die();
        }

        function setValue( $key, $value )
        {
            throw( new qException( "qConfigAbstractStorage::setValue: This method must be implemented by child classes." ));
            die();
        }

        function getAsArray()
        {
            throw( new qException( "qConfigAbstractStorage::getAsArray: This method must be implemented by child classes." ));
            die();
        }

        function reload()
        {
            throw( new qException( "qConfigAbstractStorage::reload: This method must be implemented by child classes." ));
            die();
        }

        function getConfigFileName()
        {
            throw( new qException( "qConfigAbstractStorage::getConfigFileName: This method must be implemented by child classes." ));
            die();
        }

        function getKeys()
        {
            throw( new qException( "qConfigAbstractStorage::getKeys: This method must be implemented by child classes." ));
            die();
        }

        function getValues()
        {
            throw( new qException( "qConfigAbstractStorage::getValues: This method must be implemented by child classes." ));
            die();
        }

        function saveValue( $key, $value )
        {
            throw( new qException( "qConfigAbstractStorage::saveValue: This method must be implemented by child classes." ));
            die();
        }

        function save()
        {
            throw( new qException( "qConfigAbstractStorage::saveValue: This method must be implemented by child classes." ));
            die();
        }
    }
?>

<?php

    include_once("framework/class/object/exception.class.php" );

    /**
     * This is the highest class on the top of our hierarchy. Provides some common methods
     * useful to deal with objects, an also some commodity methods for debugging such as
     * toString, which will dump the names and the values of the attributes of the object.
     * All the objects should inherit from this one and call this constructor manually, due
     * to PHP not automatically calling the parent's class constructor when inheriting.
     */
    class Object {

        var $_objId;

        /**
         * Constructor
         */
        function Object()
        {
            // removed for performance reasons!
            //$this->_objId = uniqid($this->className()."__");
        }

        function __getObjectId()
        {
            return $this->_objId;
        }

        /**
         * Returns a string with a representation of the class
         * @return The string representing the object
         */
        function toString()
        {
            // returns the name of the class
            $ret_str = get_class( $this )." ".$this->_dumpVars();

            return $ret_str;
        }

        function _dumpVars()
        {
            $vars = get_object_vars( $this );

            $keys = array_keys( $vars );

            $res = "[";

            foreach( $keys as $key )
                $res .= " ".$key."=".$vars[$key];

            $res .= " ]";

            return $res;
        }

        /**
         * Returns the name of the class
         * @return String with the name of the class
         */
        function className()
        {
            return get_class( $this );
        }

        /**
         * Returns the name of the parent class
         * @return String containing the name of the parent class
         */
        function getParentClass()
        {
            $parent_class_name = get_parent_class( $this );

            return $parent_class_name;
        }

        /**
         * Returns true if the current class is a subclass of the given
         * class
         * @param $object The object.
         * @return True if the object is a subclass of the given object or false otherwise.
         */
        function isSubclass( $object )
        {
            return is_subclass_of( $this, $object->className());
        }

        /**
         * Returns an array containing the methods available in this class
         * @return Array containing all the methods available in the object.
         */
        function getMethods()
        {
            return get_class_methods( $this );
        }

        /**
         * Returns true if the class is of the given type.
         *
         * @param object Object
         * @return Returns true if they are of the same type or false otherwise.
         */
        function typeOf( $object )
        {
            return is_a( $this, $object->className());
        }
    }
?>

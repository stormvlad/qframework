<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qDbObject extends qObject
    {
        var $_fields;
        /**
        * Add function info here
        */
        function qDbObject($params = null)
        {
            $this->qObject();
            $this->_fields = new qProperties($params);
        }

        /**
        * Add function info here
        */
        function addField($fieldName)
        {
            $this->_fields->setValue($fieldName, null);
        }

        /**
        * Add function info here
        */
        function getValue($fieldName)
        {
            return $this->_fields->getValue($fieldName);
        }

        /**
        * Add function info here
        */
        function setValue($fieldName, $value)
        {
            $this->_fields->setValue($fieldName, $value);
        }

        /**
        * Add function info here
        */
        function fieldExists($fieldName)
        {
            return $this->_fields->keyExists($fieldName);
        }

        /**
        * Add function info here
        */
        function map($row)
        {
            foreach ($row as $key => $value)
            {
                if ($this->fieldExists($key))
                {
                    $this->_fields->setValue($key, $value);
                }
            }
        }
    }
?>

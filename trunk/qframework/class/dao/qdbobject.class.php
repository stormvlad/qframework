<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qDbObject extends qObject
    {
        var $_fields;
        var $_idFields;
        var $_outerFields;

        /**
        * Add function info here
        */
        function qDbObject($fields = null)
        {
            $this->qObject();

            $this->_fields      = new qProperties($fields);
            $this->_idFields    = array();
            $this->_outerFields = new qProperties();
        }

        /**
        * Add function info here
        */
        function addIdField($fieldName)
        {
            $this->_idFields[] = $fieldName;
        }

        /**
        * Add function info here
        */
        function addFields($fields)
        {
            foreach ($fields as $fieldName => $fieldValue)
            {
                $this->_fields->setValue($fieldName, $fieldValue);
            }
        }

        /**
        * Add function info here
        */
        function addField($fieldName, $fieldValue = null)
        {
            $this->_fields->setValue($fieldName, $fieldValue);
        }

        /**
        * Add function info here
        */
        function addOuterFields($fields)
        {
            foreach ($fields as $fieldName => $fieldValue)
            {
                $this->_outerFields->setValue($fieldName, $fieldValue);
            }
        }

        /**
        * Add function info here
        */
        function addOuterField($fieldName, $fieldValue = null)
        {
            $this->_outerFields->setValue($fieldName, $fieldValue);
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
        function getOuterValue($fieldName)
        {
            return $this->_outerFields->getValue($fieldName);
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
        function setOuterValue($fieldName, $value)
        {
            $this->_outerFields->setValue($fieldName, $value);
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
        function outerFieldExists($fieldName)
        {
            return $this->_outerFields->keyExists($fieldName);
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
                else if ($this->outerFieldExists($key))
                {
                    $this->_outerFields->setValue($key, $value);
                }
            }
        }

        /**
        * Add function info here
        */
        function getFields()
        {
            return $this->_fields->getAsArray();
        }

        /**
        * Add function info here
        */
        function getOuterFields()
        {
            return $this->_outerFields->getAsArray();
        }

        /**
        * Add function info here
        */
        function getIdFields()
        {
            return $this->_idFields;
        }

        /**
        * Add function info here
        */
        function getFieldsCount()
        {
            $this->_fields->count();
        }

        /**
        * Add function info here
        */
        function getOuterFieldsCount()
        {
            $this->_outerFields->count();
        }
    }
?>
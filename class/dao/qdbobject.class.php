<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");

    /**
     * @brief Clase base que representa un objeto de datos     
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 19:36
     * @version 1.0
     * @ingroup dao
     * @see qDaoGenerator Estas clases pueden generarse automáticamente
     */
    class qDbObject extends qObject
    {
        var $_fields;
        var $_idFields;
        var $_outerFields;
        var $_nullFields;

        /**
        * Add function info here
        */
        function qDbObject($fields = null)
        {
            $this->qObject();

            $this->_fields      = new qProperties($fields);
            $this->_idFields    = array();
            $this->_outerFields = new qProperties();
            $this->_nullFields  = array();
        }

        /**
        * Add function info here
        */
        function addIdFields($fields)
        {
            $this->_idFields[] = $fields;
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
        function removeIdFields()
        {
            $this->_idFields = array();
        }

        /**
        * Add function info here
        */
        function removeIdField($fieldName)
        {
            $this->_idFields = array_diff($this->_idFields, array($fieldName));
        }

        /**
        * Add function info here
        */
        function addFields($fields)
        {
            foreach ($fields as $fieldName => $fieldValue)
            {
                $this->_fields->setValue($fieldName, $fieldValue);
                $this->_nullFields[$fieldName] = false;
            }
        }

        /**
        * Add function info here
        */
        function addField($fieldName, $fieldValue = null)
        {
            $this->_fields->setValue($fieldName, $fieldValue);
            $this->_nullFields[$fieldName] = false;
        }

        /**
        * Add function info here
        */
        function removeFields()
        {
            $this->_fields     = new qProperties($fields);
            $this->_nullFields = array();
        }

        /**
        * Add function info here
        */
        function removeField($fieldName)
        {
            $this->_fields->removeValue($fieldName);
            unset($this->_nullFields[$fieldName]);
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
        function removeOuterFields()
        {
            $this->_outerfields = new qProperties($fields);
        }

        /**
        * Add function info here
        */
        function removeOuterField($fieldName)
        {
            $this->_outerfields->removeValue($fieldName);
        }

        /**
        * Add function info here
        */
        function getValue($fieldName)
        {
            if ($this->fieldExists($fieldName))
            {
                return $this->_fields->getValue($fieldName);
            }
            else
            {
                return $this->_outerFields->getValue($fieldName);
            }
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
            if ($this->fieldExists($fieldName))
            {
                $this->_fields->setValue($fieldName, $value);

                if (empty($value))
                {
                    $this->_nullFields[$fieldName] = true;
                }
            }
            else
            {
                $this->_outerFields->setValue($fieldName, $value);
            }
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
            if (is_object($row))
            {
                $row = get_object_vars($row);
            }

            foreach ($row as $key => $value)
            {
                if ($this->fieldExists($key))
                {
                    $this->setValue($key, $value);
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
        * Get all object fields and the outer fields added
        */
        function getAllFields()
        {
            return array_merge($this->getFields(), $this-> getOuterFields());
        }

        /**
        * Add function info here
        */
        function &getIdFields()
        {
            return $this->_idFields;
        }
        
        /**
        * An alias function for getIdFields
        */
        function &getPrimaryKeyFields()
        {
            return $this->getIdFields();
        }

        /**
        * Returns an id that differentiates this object from others of its class.
        */
        function &getPrimaryKey()
        {
            if (!count($this->_idFields))
            {
                return false;
            }
            
            return $this->getValue($this->_idFields[0]);
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

        /**
        * Add function info here
        */
        function hasNullValue($fieldName)
        {
            return $this->_nullFields[$fieldName];
        }
    }
?>
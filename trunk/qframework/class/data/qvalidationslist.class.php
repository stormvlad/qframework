<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    define("ERROR_RULE_IS_EMPTY", "error_rule_is_empty");

    /**
     * This is an implementation of the 'Strategy' pattern as it can be seen
     * http://www.phppatterns.com/index.php/article/articleview/13/1/1/. Here we use
     * this pattern to validate data received from forms. Its is useful since for example
     * we check in many places if a 'postId' is valid or not. We can put the
     * checkings inside the class and simply reuse this class wherever we want. If we ever
     *`change the format of the postId parameter, we only have to change the code of the
     * class that validates it and it will be automatically used everywhere.
     */
    class qValidationsList extends qObject
    {
        var $_validations;
        var $_required;
        var $_errors;

        /**
         * The constructor does nothing.
         */
        function qValidationsList()
        {
            $this->qObject();

            $this->_validations = array();
            $this->_required    = array();
            $this->_errors      = array();
        }

        /**
        *    Add function info here
        **/
        function isRequired($name)
        {
            if (!array_key_exists($name, $this->_required))
            {
                return false;
            }

            return $this->_required[$name];
        }

        /**
        *    Add function info here
        **/
        function setRequired($name, $required = true)
        {
            $this->_required[$name] = $required;
        }

        /**
        *    Add function info here
        **/
        function addValidation($name, &$validation)
        {
            $this->_validations[$name][] = &$validation;
        }

        /**
        *    Add function info here
        **/
        function &getValidations($name = null)
        {
            if (empty($name) || !array_key_exists($name, $this->_validations))
            {
                return $this->_validations;
            }
            else
            {
                return $this->_validations[$name];
            }
        }

        /**
        *    Add function info here
        **/
        function setError($name, $error)
        {
            $this->_errors[$name] = $error;
        }

        /**
        *    Add function info here
        **/
        function &getErrors($name = null)
        {
            if (empty($name) || !array_key_exists($name, $this->_errors))
            {
                return $this->_errors;
            }
            else
            {
                return $this->_errors[$name];
            }
        }

        /**
        *    Add function info here
        **/
        function _validateValue($name, $value)
        {
            if ($value === "")
            {
                if ($this->isRequired($name))
                {
                    $this->setError($name, ERROR_RULE_IS_EMPTY);
                    return false;
                }
                else
                {
                    return true;
                }
            }

            if (array_key_exists($name, $this->_validations) && is_array($this->_validations[$name]))
            {
                foreach ($this->_validations[$name] as $validation)
                {
                    if (!$validation->validate($value))
                    {
                         $this->setError($name, $validation->getError());
                         return false;
                    }
                }
            }

            return true;
        }

        /**
        *    Add function info here
        **/
        function validate($values)
        {
            $result = true;

            foreach ($this->_required as $name => $required)
            {
                if ($required && !array_key_exists($name, $values))
                {
                    $this->setError($name, ERROR_RULE_IS_EMPTY);
                    $result = false;
                }
                // Added to check required variables from $_FILES
                else if ($required && array_key_exists($name, $values) && is_array($values[$name]) && $values[$name]["error"] == 4)
                {
                    $this->setError($name, ERROR_RULE_IS_EMPTY);
                    $result = false;
                }
            }

            foreach ($values as $name => $value)
            {
                $result &= $this->_validateValue($name, $value);
            }

            return $result;
        }
    }
?>
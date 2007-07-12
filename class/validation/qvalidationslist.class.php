<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/misc/qutils.class.php");

    define("ERROR_RULE_IS_EMPTY", "error_rule_is_empty");

    /**
     * @brief Conjunto de validadores y reglas.
     *
     * This is an implementation of the 'Strategy' pattern as it can be seen
     * http://www.phppatterns.com/index.php/article/articleview/13/1/1/. Here we use
     * this pattern to validate data received from forms. Its is useful since for example
     * we check in many places if a 'postId' is valid or not. We can put the
     * checkings inside the class and simply reuse this class wherever we want. If we ever
     *`change the format of the postId parameter, we only have to change the code of the
     * class that validates it and it will be automatically used everywhere.
     *
     * @author  qDevel - info@qdevel.com
     * @date    05/03/2005 19:22
     * @version 1.0
     * @ingroup validation
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
        function validate($values)
        {
            $result = true;
            $falses = array();

            foreach ($this->_required as $name => $required)
            {
                $value = qUtils::getValueFromKeyName($name, $values);

                if ($required && trim($value) === "")
                {
                    $this->setError(qUtils::normalizeKeyName($name), ERROR_RULE_IS_EMPTY);
                    $result = false;
                    $falses[$name] = true;
                }
                // Added to check required variables from $_FILES
                else if ($required && !empty($value) && is_array($values[$name]) && $values[$name]["error"] == 4)
                {
                    $this->setError(qUtils::normalizeKeyName($name), ERROR_RULE_IS_EMPTY);
                    $result = false;
                    $falses[$name] = true;
                }
            }

            foreach ($this->_validations as $name => $validations)
            {
                if (empty($falses[$name]))
                {
                    $value = qUtils::getValueFromKeyName($name, $values);

                    if ($value !== ""  && $value !== NULL)
                    {
                        foreach ($validations as $validation)
                        {
                            if (!$validation->validate($value))
                            {
                                $this->setError(qUtils::normalizeKeyName($name), $validation->getError());
                                $result = false;
                                break;
                            }
                        }
                    }
                }
            }

            return $result;
        }
    }
?>
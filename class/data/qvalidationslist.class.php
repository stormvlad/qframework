<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

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
        var $_errors;

        /**
         * The constructor does nothing.
         */
        function qValidationsList()
        {
            $this->qObject();

            $this->_validations = array();
            $this->_errors      = array();
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
        function _setError($name, $error)
        {
            $this->_errors[$name] = $error;
        }

        /**
        *    Add function info here
        **/
        function getErrors($name = null)
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
            $i            = 0;
            $result       = true;
            $nonEmptyRule = false;

            if (array_key_exists($name, $this->_validations) && is_array($this->_validations[$name]))
            {
                foreach ($this->_validations[$name] as $validation)
                {
                    if ($validation->typeOf("qNonEmptyRule") || $validation->typeOf("qRangeRule"))
                    {
                        $nonEmptyRule = $i;
                        break;
                    }

                    $i++;
                }

                foreach ($this->_validations[$name] as $validation)
                {
                    if ($validation->typeOf("qValidator"))
                    {
                        $result = $validation->validate($value);
                    }
                    else if ($validation->typeOf("qRule"))
                    {
                        $result = $validation->check($value);
                    }

                    if (!$result)
                    {
                        if (empty($value) && $nonEmptyRule === false)
                        {
                            return true;

                        }
                        else
                        {
                            $this->_setError($name, $validation->getError());
                            return $result;
                        }
                    }
                }
            }


            return $result;
        }

        /**
        *    Add function info here
        **/
        function validate($values)
        {
            $result = true;

            foreach ($values as $name => $value)
            {
                $result &= $this->_validateValue($name, $value);
            }

            return $result;
        }
    }
?>
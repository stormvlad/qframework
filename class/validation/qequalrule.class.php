<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qrule.class.php");

    define("ERROR_RULE_VALUES_NOT_EQUAL", "error_rule_values_not_equal");

    /**
     * @brief Comprueba que un dato es igual a uno predefinido
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
     * @ingroup validation rule
     */
    class qEqualRule extends qRule
    {
        var $_equalValue;

        /**
         * The constructor does nothing.
         */
        function qEqualRule($equalValue)
        {
            $this->qRule();
            $this->_equalValue = $equalValue;
        }

        /**
         * Add function info here
         */
        function getEqualValue()
        {
            return $this->_equalValue;
        }

        /**
         * Add function info here
         */
        function setEqualValue($equalValue)
        {
            $this->_equalValue = $equalValue;
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate($value)
        {
            if ($this->_equalValue == $value)
            {
                $this->setError(false);
                return true;
            }
            else
            {
                $this->setError(ERROR_RULE_VALUES_NOT_EQUAL);
                return false;
            }
        }
    }
?>
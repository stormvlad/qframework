<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qrule.class.php");

    define(DEFAULT_RULE_CASE_SENSITIVE, true);
    define(ERROR_RULE_VALUES_NOT_EQUAL, "error_rule_values_not_equal");

    /**
     * This is an implementation of the 'Strategy' pattern as it can be seen
     * http://www.phppatterns.com/index.php/article/articleview/13/1/1/. Here we use
     * this pattern to validate data received from forms. Its is useful since for example
     * we check in many places if a 'postId' is valid or not. We can put the
     * checkings inside the class and simply reuse this class wherever we want. If we ever
     *`change the format of the postId parameter, we only have to change the code of the
     * class that validates it and it will be automatically used everywhere.
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
                $this->_setError(false);
                return true;
            }
            else
            {
                $this->_setError(ERROR_RULE_VALUES_NOT_EQUAL);
                return false;
            }
        }
    }
?>
<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qregexprule.class.php");

    define(UINT_RULE_REG_EXP, "^([1-9][0-9]*)|0$");
    define(ERROR_RULE_UINT_FORMAT_WRONG, "error_rule_uint_format_wrong");

    /**
     * This is an implementation of the 'Strategy' pattern as it can be seen
     * http://www.phppatterns.com/index.php/article/articleview/13/1/1/. Here we use
     * this pattern to validate data received from forms. Its is useful since for example
     * we check in many places if a 'postId' is valid or not. We can put the
     * checkings inside the class and simply reuse this class wherever we want. If we ever
     *`change the format of the postId parameter, we only have to change the code of the
     * class that validates it and it will be automatically used everywhere.
     */
    class qUIntRule extends qRegExpRule
    {
        /**
         * The constructor does nothing.
         */
        function qUIntRule()
        {
            $this->qRegExpRule(UINT_RULE_REG_EXP, false);
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate($value)
        {
            if (parent::validate($value))
            {
                $this->_setError(false);
                return true;
            }
            else
            {
                $this->_setError(ERROR_RULE_UINT_FORMAT_WRONG);
                return false;
            }
        }
    }
?>
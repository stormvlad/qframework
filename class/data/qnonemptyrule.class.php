<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qrule.class.php");

    define(ERROR_RULE_IS_EMPTY, "error_rule_is_empty");

    /**
     * This is an implementation of the 'Strategy' pattern as it can be seen
     * http://www.phppatterns.com/index.php/article/articleview/13/1/1/. Here we use
     * this pattern to validate data received from forms. Its is useful since for example
     * we check in many places if a 'postId' is valid or not. We can put the
     * checkings inside the class and simply reuse this class wherever we want. If we ever
     *`change the format of the postId parameter, we only have to change the code of the
     * class that validates it and it will be automatically used everywhere.
     */
    class qNonEmptyRule extends qRule
    {
        /**
         * The constructor does nothing.
         */
        function qNonEmptyRule()
        {
            $this->qRule();
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate($value)
        {
            if (empty($value))
            {
                $this->_setError(ERROR_RULE_IS_EMPTY);
                return false;
            }
            else
            {
                $this->_setError(false);
                return true;
            }
        }
    }
?>
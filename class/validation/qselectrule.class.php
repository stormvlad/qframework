<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qrule.class.php");

    define("ERROR_RULE_WRONG_SELECT_VALUE", "error_rule_wrong_select_value");

    /**
     * @brief Determina si se ha seleccionado un dato en una lista <code>SELECT</code>.
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
    class qSelectRule extends qRule
    {
        /**
         * The constructor does nothing.
         */
        function qSelectRule()
        {
            $this->qRule();
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate($value, $field = null)
        {
            if (empty($value))
            {
                $this->setError(ERROR_RULE_WRONG_SELECT_VALUE);
                return false;
            }

            return true;
        }
    }
?>
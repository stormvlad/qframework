<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qvalidation.class.php");

    define("DEFAULT_RULE_CASE_SENSITIVE", true);

    /**
      * @defgroup rule Reglas
      * @ingroup validation
      */

    /**
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
    class qRule extends qValidation
    {
        /**
         * The constructor does nothing.
         */
        function qRule()
        {
            $this->qValidation();
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate($value)
        {
            throw(new qException("qRule::validate: This method must be implemented by child classes."));
            die();
        }
    }
?>
<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qregexprule.class.php");

    // define("EMAIL_FORMAT_RULE_REG_EXP", "^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}$");
    define("EMAIL_FORMAT_RULE_REG_EXP", "^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$");
    define("ERROR_RULE_EMAIL_FORMAT_WRONG", "error_rule_email_format_wrong");

    /**
     * @brief Determina si el formato de la dirección de correo electrónico es correcto.
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
    class qEmailFormatRule extends qRegExpRule
    {
        /**
         * The constructor does nothing.
         */
        function qEmailFormatRule()
        {
            $this->qRegExpRule(EMAIL_FORMAT_RULE_REG_EXP, false);
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate($value, $field = null)
        {
            if (parent::validate($value, $field))
            {
                $this->setError(false);
                return true;
            }
            else
            {
                $this->setError(ERROR_RULE_EMAIL_FORMAT_WRONG);
                return false;
            }
        }
    }
?>
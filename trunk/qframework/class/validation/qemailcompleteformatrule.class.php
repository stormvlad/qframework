<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qemailformatrule.class.php");

    define("EMAIL_COMPLETE_FORMAT_RULE_REG_EXP", "^\"?[^<]+\"?[ ]+\\<" . substr(EMAIL_FORMAT_RULE_REG_EXP, 1, -1) . "\\>$");
    define("ERROR_RULE_EMAIL_COMPLETE_FORMAT_WRONG", "error_rule_email_complete_format_wrong");

    /**
     * @brief Determina si el formato de la dirección de correo electrónico es de formato completo. Por ejemplo:
     * "Tu nombre" <tunombre@hotmail.com>, Nombre <nombre@hotmail.com>...
     *
     * @author  qDevel - info@qdevel.com
     * @date    05/03/2005 19:22
     * @version 1.0
     * @ingroup validation rule
     */
    class qEmailCompleteFormatRule extends qRegExpRule
    {
        /**
         * The constructor does nothing.
         */
        function qEmailCompleteFormatRule()
        {
            $this->qRegExpRule(EMAIL_COMPLETE_FORMAT_RULE_REG_EXP, false);
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate($value)
        {
            if (parent::validate($value))
            {
                $this->setError(false);
                return true;
            }
            else
            {
                $this->setError(ERROR_RULE_EMAIL_COMPLETE_FORMAT_WRONG);
                return false;
            }
        }
    }
?>
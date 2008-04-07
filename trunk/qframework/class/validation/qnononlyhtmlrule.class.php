<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qrule.class.php");

    define("ERROR_RULE_ONLY_HTML_VALUE", "error_rule_only_html_value");

    /**
     * @brief Comprueba que el dato no contenga únicamente tags html
     *
     * @author  qDevel - info@qdevel.com
     * @date    25/07/2006 18:37
     * @version 1.0
     * @ingroup validation rule
     */
    class qNonOnlyHtmlRule extends qRule
    {
        /**
         * The constructor does nothing.
         */
        function qNonOnlyHtmlRule()
        {
            $this->qRule();
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate($value, $field = null)
        {
            include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qformat.class.php");
            $value = trim(qFormat::stripTags($value));
            
            if (empty($value))
            {
                $this->setError(ERROR_RULE_ONLY_HTML_VALUE);
                return false;
            }
            else
            {
                $this->setError(false);
                return true;
            }
        }
    }
?>
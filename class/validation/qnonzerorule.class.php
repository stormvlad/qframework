<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qrule.class.php");

    define("ERROR_RULE_ZERO_VALUE", "error_rule_zero_value");

    /**
     * @brief Comprueba que el dato no sea zero (o vacío)
     *
     * @author  qDevel - info@qdevel.com
     * @date    05/03/2005 19:22
     * @version 1.0
     * @ingroup validation rule
     */
    class qNonZeroRule extends qRule
    {
        /**
         * The constructor does nothing.
         */
        function qNonZeroRule()
        {
            $this->qRule();
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate($value)
        {
            if (empty($value) || preg_match("/^0+[,.]0+$/", $value))
            {
                $this->setError(ERROR_RULE_ZERO_VALUE);
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
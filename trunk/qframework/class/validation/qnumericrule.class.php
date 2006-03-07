<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qregexprule.class.php");

    define("NUMERIC_RULE_REG_EXP", "^[0-9]+$");
    define("ERROR_RULE_NUMERIC_FORMAT_WRONG", "error_rule_numeric_format_wrong");

    /**
     * @brief Comprueba si un valor es numérico
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
    class qNumericRule extends qRegExpRule
    {
        var $decimalSymbol;
        var $thousandsSeparator;
        var $thousandsSeparatorNullAllowed;
        
        /**
         * The constructor does nothing.
         */
        function qNumericRule()
        {
            $this->qRegExpRule(NUMERIC_RULE_REG_EXP, false);
        }

        /**
        * Add function info here
        */
        function getDecimalSymbol()
        {
            return $this->_decimalSymbol;
        }

        /**
        * Add function info here
        */
        function setDecimalSymbol($symbol)
        {
            $this->_decimalSymbol = $symbol;
        }

        /**
        * Add function info here
        */
        function getThousandsSeparator()
        {
            return $this->_thousandsSeparator;
        }

        /**
        * Add function info here
        */
        function setThousandsSeparator($separator)
        {
            $this->_thousandsSeparator = $separator;
        }

        /**
        * Add function info here
        */
        function isThousandsSeparatorNullAllowed()
        {
            return $this->_thousandsSeparatorNullAllowed;
        }

        /**
        * Add function info here
        */
        function setThousandsSeparatorNullAllowed($allowed)
        {
            $this->_thousandsSeparatorNullAllowed = $allowed;
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
                $this->setError(ERROR_RULE_NUMERIC_FORMAT_WRONG);
                return false;
            }
        }
    }
?>
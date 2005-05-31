<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qregexprule.class.php");

    define("ERROR_RULE_FLOAT_FORMAT_WRONG", "error_rule_float_format_wrong");

    /**
    * FloatValidator class
    */
    class qFloatRule extends qRegExpRule
    {
        var $decimalSymbol;
        var $thousandsSeparator;
        var $thousandsSeparatorNullAllowed;

        /**
        * Constructor
        */
        function qFloatRule($decimalSymbol = ",", $thousandsSeparator = ".", $thousandsSeparatorNullAllowed = true)
        {
            $this->qRegExpRule("");

            $this->_decimalSymbol                 = $decimalSymbol;
            $this->_thousandsSeparator            = $thousandsSeparator;
            $this->_thousandsSeparatorNullAllowed = $thousandsSeparatorNullAllowed;
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
        * Add function info here
        */
        function validate($value)
        {
            $decimalSymbol      = $this->getDecimalSymbol();
            $thousandsSeparator = $this->getThousandsSeparator();
            $regExp             = "^(([0-9]+([" . $decimalSymbol . "][0-9]+)?))$";

            if (!empty($thousandsSeparator))
            {
                $regExp .= "|^([0-9]{1,3}([" . $thousandsSeparator . "][0-9]{3})*([" . $decimalSymbol . "][0-9]+)?)$";
            }

            $this->setRegExp($regExp);

            if (parent::validate($value))
            {
                $this->setError(false);
                return true;
            }
            else
            {
                $this->setError(ERROR_RULE_FLOAT_FORMAT_WRONG);
                return false;
            }
        }
    }
?>

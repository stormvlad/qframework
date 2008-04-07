<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qnumericrule.class.php");

    define("ERROR_RULE_FLOAT_FORMAT_WRONG", "error_rule_float_format_wrong");

    /**
    * FloatValidator class
    */
    class qFloatRule extends qNumericRule
    {
        var $decimalSymbol;
        var $thousandsSeparator;
        var $thousandsSeparatorNullAllowed;

        /**
        * Constructor
        */
        function qFloatRule($decimalSymbol = ",", $thousandsSeparator = ".", $thousandsSeparatorNullAllowed = true)
        {
            $this->qNumericRule();

            $this->setDecimalSymbol($decimalSymbol);
            $this->setThousandsSeparator($thousandsSeparator);
            $this->setThousandsSeparatorNullAllowed($thousandsSeparatorNullAllowed);
        }

        /**
        * Add function info here
        */
        function validate($value, $field = null)
        {
            $decimalSymbol      = $this->getDecimalSymbol();
            $thousandsSeparator = $this->getThousandsSeparator();
            $regExp             = "^(([0-9]+([" . $decimalSymbol . "][0-9]+)?))$";

            if (!empty($thousandsSeparator))
            {
                $regExp .= "|^([0-9]{1,3}([" . $thousandsSeparator . "][0-9]{3})*([" . $decimalSymbol . "][0-9]+)?)$";
            }

            $this->setRegExp($regExp);

            if (parent::validate($value, $field))
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

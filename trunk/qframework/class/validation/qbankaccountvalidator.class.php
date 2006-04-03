<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qvalidator.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qregexprule.class.php");

    define("ERROR_VALIDATOR_NIF_FORMAT_WRONG", "error_validator_nif_format_wrong");
    define("ERROR_VALIDATOR_NIF_LETTER_WRONG", "error_validator_nif_letter_wrong");

    /**
     * Extends the validator class to determine wether an email address is valid or not.
     */
    class qBankAccountValidator extends qValidator
    {
        /**
        * Constructor
        */
        function qBankAccountValidator()
        {
            $this->qValidator();
        }

        /**
        * Add function info here
        */
        function validate($value)
        {
            $value = trim($value);

            if (strlen($value) != 20)
            {
                $this->setError(ERROR_VALIDATOR_BANK_ACCOUNT_FORMAT_WRONG);
                return false;
            }
            
            $dc = substr($value,8,2);
            
            $dcC = $this->calculateControlDigit("00" . substr($value,0,8)) . $this->calculateControlDigit(substr($value,-10));
            
            return ($dc == $dcC)
        }
        
        
        function calculateControlDigit($value)
        {
            $multiplicador = Array(1,2,4,8,5,10,9,7,3,6);
            $total = 0;
            for ($ni = 0; $ni < strlen($value); $ni++)
            {
                $total += substr($value,$ni,1) * $multiplicador[$ni];
            }
            $digit = 11 - ($total % 11);
            if ($digit == 11) { return 0; }
            if ($digit == 10) { return 1; }
            return $digit;     
        }
    }
?>
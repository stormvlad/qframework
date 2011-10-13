<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qvalidator.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qregexprule.class.php");

    define("ERROR_VALIDATOR_NIF_FORMAT_WRONG", "error_validator_nif_format_wrong");
    define("ERROR_VALIDATOR_NIF_LETTER_WRONG", "error_validator_nif_letter_wrong");

    /**
     * Extends the validator class to determine wether an email address is valid or not.
     */
    class qNifValidator extends qValidator
    {
        /**
        * Constructor
        */
        function qNifValidator()
        {
            $this->qValidator();
        }

        /**
        * Add function info here
        */
        function validate($value, $field = null)
        {
            $value = trim($value);

            switch (strtoupper(substr($value,0,1)))
            {
                case "0":
                case "1":
                case "2":
                case "3":
                case "4":
                case "5":
                case "6":
                case "7":
                case "8":
                case "9":
                    return $this->validateNif($value);
                    break;
                case "X":
                case "Y":
                case "Z":
                    return $this->validateNie($value);
                    break;
                default:
                    return $this->validateCif($value);
                    break;
            }

        }
        
        /**
        * Add function info here
        */
        function validateCif($value)
        {
            if (strlen($value) != 9)
            {
                $this->setError(ERROR_VALIDATOR_NIF_FORMAT_WRONG);
                return false;
            }

            $rule = new qRegExpRule("[a-z][0-9]{7}.", false);
            
            if (!$rule->validate($value))
            {
                $this->setError(ERROR_VALIDATOR_NIF_FORMAT_WRONG);
                return false;
            }
            
            $value = strtoupper($value);
            $nR1 = 0;
            $nR2 = 0;
    
            for($ni = 1; $ni < 8; $ni++)
            {
                $char = substr($value, $ni, 1);
                
                if (!($ni % 2))
                {
                    $nR1 += $char;
                }
                else
                {
                    $nR2 += ((2 * $char) % 10) + floor((2 * $char) / 10);
                }
            }    
            
            $res = (($nR1 + $nR2) % 10);
            $res = ((10 - $res) % 10);
            $char = chr(64 + $res);
            
            if (substr($value, -1) == $res || ((substr($value, 0, 1) == "Q" || substr($value, 0, 1) == "P" || substr($value, 0, 1) == "N" || substr($value, 0, 1) == "R") && substr($value, -1) == $char))
            {
                $this->setError(false);
                return true;
            }
            else
            {
                $this->setError(ERROR_VALIDATOR_NIF_LETTER_WRONG);
                return false;
            }
        }
        
        /**
        * Add function info here
        */
        function validateNif($value)
        {
            if (strlen($value) != 9)
            {
                $this->setError(ERROR_VALIDATOR_NIF_FORMAT_WRONG);
                return false;
            }

            $rule = new qRegExpRule("[0-9]{8}[a-z]", false);
            
            if (!$rule->validate($value))
            {
                $this->setError(ERROR_VALIDATOR_NIF_FORMAT_WRONG);
                return false;
            }
            
            $letters = "TRWAGMYFPDXBNJZSQVHLCKE";
            $nif     = intVal(substr($value, 0, -1));
            $ind     = $nif % 23;
            $letter  = substr($letters, $ind, 1);

            if (strtoupper(substr($value, -1)) != $letter)
            {
                $this->setError(ERROR_VALIDATOR_NIF_LETTER_WRONG);
                return false;
            }
            else
            {
                $this->setError(false);
                return true;
            }
        }
        
        /**
        * Add function info here
        */
        function validateNie($value)
        {
            if (strlen($value) != 9)
            {
                $this->setError(ERROR_VALIDATOR_NIF_FORMAT_WRONG);
                return false;
            }

            $rule = new qRegExpRule("[XYZ][0-9]{7,8}[a-z]", false);

            if (!$rule->validate($value))
            {
                $this->setError(ERROR_VALIDATOR_NIF_FORMAT_WRONG);
                return false;
            }

            return $this->validateNif("0" . substr($value,1));
        }
    }
?>
<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qvalidator.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qregexprule.class.php");

    define("ERROR_VALIDATOR_NIF_FORMAT_WRONG", "error_validator_nif_format_wrong");
    define("ERROR_VALIDATOR_NIF_LETTER_WRONG", "error_validator_nif_letter_wrong");

    /**
     * Extends the validator class to determine wether an email address is valid or not.
     *
     * @author  qDevel - info@qdevel.com
     * @date    05/03/2005 19:22
     * @version 1.0
     * @ingroup validation validator
     */
    class qNifValidator extends qValidator
    {
        /**
        * Constructor
        */
        function qNifValidator()
        {
            $this->qValidator();
            $this->addRule(new qRegExpRule("[0-9]{8}[a-z]", false));
        }

        /**
        * Add function info here
        */
        function validate($value)
        {
            if (!parent::validate($value))
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
    }
?>
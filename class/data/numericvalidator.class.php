<?php

    include_once("framework/class/data/validator.class.php" );

    define(NUMERIC_REG_EXP, "[0-9]+");

    /**
     * Checks that a string is not empty. That could be because it is
     * 'null', there is really nothing ('') or it is just a bunch of
     * blank spaces.
     *
     * Classes can extend this one to provide a custom error message.
     */
    class NumericValidator extends Validator {

        function NumericValidator($value)
        {
            $this->Validator($value);
        }

        function validate()
        {
            if (ereg(NUMERIC_REG_EXP, $this->_value))
            {
                $this->_valid = false;
                $this->setMessage("numeric_validator_wrong_format");
            }
            else
            {
                $this->_valid = true;
            }

            return $this->_valid;
        }
    }
?>
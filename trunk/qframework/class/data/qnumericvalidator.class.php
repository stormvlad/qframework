<?php

    include_once("qframework/class/data/qvalidator.class.php" );

    define(NUMERIC_REG_EXP, "[0-9]+");

    /**
     * Checks that a string is not empty. That could be because it is
     * 'null', there is really nothing ('') or it is just a bunch of
     * blank spaces.
     *
     * Classes can extend this one to provide a custom error message.
     */
    class qNumericValidator extends qValidator {

        function qNumericValidator($value)
        {
            $this->qValidator($value);
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
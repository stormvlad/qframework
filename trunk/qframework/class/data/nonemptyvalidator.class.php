<?php

    include_once("framework/class/data/validator.class.php" );

    /**
     * Checks that a string is not empty. That could be because it is
     * 'null', there is really nothing ('') or it is just a bunch of
     * blank spaces.
     *
     * Classes can extend this one to provide a custom error message.
     */
    class NonEmptyValidator extends Validator {

        function NonEmptyValidator($value)
        {
            $this->Validator($value);
        }

        function validate()
        {
            if ($this->isEmpty())
            {
                $this->_valid = false;
                $this->setMessage("nonempty_validator_empty");
            }
            else
            {
                $this->_valid = true;
            }

            return $this->_valid;
        }
    }
?>
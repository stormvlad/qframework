<?php

    include_once("qframework/class/data/qvalidator.class.php" );

    /**
     * Checks that a string is not empty. That could be because it is
     * 'null', there is really nothing ('') or it is just a bunch of
     * blank spaces.
     *
     * Classes can extend this one to provide a custom error message.
     */
    class qStringValidator extends qValidator {

        function qStringValidator($string)
        {
            $this->qValidator($string);
        }

        function validate()
        {
            if ($this->isEmpty())
            {
                $this->_valid = false;
                $this->setMessage("string_validator_empty");
            }
            else
            {
                $this->_valid = true;
            }

            return $this->_valid;
        }
    }
?>
<?php

    include_once("framework/class/data/validator.class.php" );

    define("DEFAULT_CASE_SENSITIVE", true);

    /**
     * Checks that a string is not empty. That could be because it is
     * 'null', there is really nothing ('') or it is just a bunch of
     * blank spaces.
     *
     * Classes can extend this one to provide a custom error message.
     */
    class RegExpValidator extends Validator {

        var $_regExp;
        var $_caseSensitive;

        function RegExpValidator($value, $regExp, $caseSensitive = DEFAULT_CASE_SENSITIVE)
        {
            $this->Validator($value);
            $this->_regExp = $regExp;
            $this->_caseSensitive = $caseSensitive;
        }

        function validate()
        {
            if ($this->_caseSensitive)
            {
                if (!ereg($this->_regExp, $this->_value))
                {
                    $this->_valid = false;
                    $this->setMessage("regexp_validator_not_match");
                }
                else
                {
                    $this->_valid = true;
                }
            }
            else
            {
                if (!eregi($this->_regExp, $this->_value))
                {
                    $this->_valid = false;
                    $this->setMessage("regexp_validator_not_match");
                }
                else
                {
                    $this->_valid = true;
                }
            }

            return $this->_valid;
        }
    }
?>
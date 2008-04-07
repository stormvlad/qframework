<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/validation/qrule.class.php");

    define("ERROR_RULE_REGEXP_NOT_MATCH", "error_rule_regexp_not_match");

    /**
     * @brief Comprueba que el dato coincide con una expresión regular 
     *
     * http://www.php.net/ereg
     *
     * @author  qDevel - info@qdevel.com
     * @date    05/03/2005 19:22
     * @version 1.0
     * @ingroup validation rule
     */
    class qRegExpRule extends qRule
    {
        var $_regExp;
        var $_caseSensitive;

        /**
         * The constructor does nothing.
         */
        function qRegExpRule($regExp, $caseSensitive = DEFAULT_RULE_CASE_SENSITIVE)
        {
            $this->qRule();

            $this->_regExp        = $regExp;
            $this->_caseSensitive = $caseSensitive;
        }

        /**
         * Add function info here
         */
        function getRegExp()
        {
            return $this->_regExp;
        }

        /**
         * Add function info here
         */
        function setRegExp($regExp)
        {
            $this->_regExp = $regExp;
        }

        /**
         * Add function info here
         */
        function isCaseSensitive()
        {
            return $this->_caseSensitive;
        }

        /**
         * Add function info here
         */
        function setCaseSensitive($caseSensitive = DEFAULT_RULE_CASE_SENSITIVE)
        {
            $this->_caseSensitive = $caseSensitive;
        }

        /**
         * Validates the data. Does nothing here and it must be reimplemented by
         * every child class.
         */
        function validate($value, $field = null)
        {
            if ($this->_caseSensitive && ereg($this->_regExp, $value))
            {
                $this->setError(false);
                return true;
            }
            else if (!$this->_caseSensitive && eregi($this->_regExp, $value))
            {
                $this->setError(false);
                return true;
            }
            else
            {
                $this->setError(ERROR_RULE_REGEXP_NOT_MATCH);
                return false;
            }
        }
    }
?>